import 'package:cafe_library_services/Equipment/equipment_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';
import '../Model/equipment_model.dart';
import '../Welcome/select_library.dart';

void main(){
  runApp(EquipmentListing());
}

class EquipmentListing extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: FutureBuilder<String>(
        future: getLibraryIdFromSharedPreferences(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.done) {
            if (snapshot.hasError) {
              // Handle error
              return Scaffold(
                body: Center(
                  child: Text('Error: ${snapshot.error}'),
                ),
              );
            } else {
              String libraryId = snapshot.data ?? '';
              return EquipmentListScreen(libraryId: libraryId);
            }
          } else {
            return const Scaffold(
              body: Center(
                child: CircularProgressIndicator(),
              ),
            );
          }
        },
      ),
    );
  }
}

class EquipmentListScreen extends StatefulWidget {
  final String libraryId;
  final Map<String, String>? headers;

  const EquipmentListScreen({Key? key, required this.libraryId, this.headers})
      : super(key: key);

  @override
  _EquipmentListScreenState createState() => _EquipmentListScreenState();
}

class _EquipmentListScreenState extends State<EquipmentListScreen> {
  late Future<List<EquipmentModel>> equipments;

  Future<List<EquipmentModel>> getEquipmentList() async {
    try {
      final String libraryId = await getLibraryIdFromSharedPreferences();
      final String? token = await getToken();

      var url = Uri.parse('${API.equipment}?library_id=$libraryId');
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer $token"
      };
      var response = await http.get(
          url,
          headers: header
      );

      if(response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);
          List<dynamic> aaDataList = result['data']['aaData'];
          List<EquipmentModel> equipments = aaDataList
              .map((data) => EquipmentModel.fromJson(data))
              .toList();
          return equipments;
        } catch (error) {
          print('Error decoding JSON: $error');
          return [];
        }
      }
      print('Request URL: $url');
      print('Request Headers: $header');
      print(response.statusCode);
      print(response.body);
      return [];
    } catch (error) {
      print('Error fetching equipments: $error');
      return [];
    }
  }

  late Future<List<EquipmentModel>> equipmentList;

  @override
  void initState() {
    super.initState();
    equipmentList = getEquipmentList();
    fetchData();
  }

  Future<void> fetchData() async {
    if (mounted) {
      await getEquipmentList();
      if (mounted) {
        setState(() {
          // Trigger a rebuild after data is fetched
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          title: const Text('Equipment Listing'),
          leading: IconButton(
            icon: const Icon(Icons.arrow_back),
            onPressed: () {
              Navigator.pushReplacement(
                context,
                MaterialPageRoute(
                  builder: (context) => const HomePage(libraryId: ''),
                ),
              );
            },
          ),
        ),
        body: FutureBuilder<List<EquipmentModel>>(
          future: equipmentList,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(
                child: Text('Error: ${snapshot.error}'),
              );
            } else {
              List<EquipmentModel> results = snapshot.data!;
              return ListView.builder(
                itemCount: results.length,
                itemBuilder: (context, index) {
                  return Card(
                    child: SizedBox(
                      height: 100.0,
                      child: ListTile(
                        onTap: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => EquipmentDetailsScreen(
                                id: results[index].id,
                                name: results[index].name,
                                picture: results[index].picture,
                              ),
                            ),
                          );
                        },
                        title: Row(
                          children: [
                            Container(
                              height: 60,
                              width: 60,
                              decoration: BoxDecoration(
                                color: Colors.green,
                                borderRadius: BorderRadius.circular(10),
                              ),
                              child: Center(
                                child: Image.network(
                                  results[index].picture,
                                  width: double.infinity,
                                  height: 150.0,
                                  fit: BoxFit.cover,
                                  errorBuilder: (context, error, stackTrace) {
                                    // Handle image loading error
                                    return const Icon(Icons.error);
                                  },
                                ),
                              ),
                            ),
                            const SizedBox(width: 32),
                            Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  results[index].name,
                                  style: const TextStyle(
                                      fontWeight: FontWeight.bold),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ),
                  );
                },
              );
            }
          },
        ),
      ),
    );
  }

  @override
  void dispose() {
    // Clean up resources, cancel timers, etc.
    super.dispose();
  }
}
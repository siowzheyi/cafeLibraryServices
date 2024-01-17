import 'package:cafe_library_services/Controller/connection.dart';
import 'package:cafe_library_services/Penalty/penalty_record.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Model/penalty_model.dart';
import '../Welcome/select_cafe.dart';

void main() {
  runApp(PenaltyListing());
}

class PenaltyListing extends StatelessWidget {
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
              return PenaltyListScreen(libraryId: libraryId);
            }
          } else {
            // While waiting for the Future to complete, show a loading indicator
            return Scaffold(
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

class PenaltyListScreen extends StatefulWidget {
  final String libraryId;
  final Map<String, String>? headers;

  const PenaltyListScreen({Key? key, required this.libraryId, this.headers}) : super(key: key);

  @override
  _PenaltyListScreenState createState() => _PenaltyListScreenState();
}

class FetchBooking {
  late List<PenaltyModel> penalties;

  Future<List<PenaltyModel>> fetchPenaltyRecords() async {
    // Replace the URL with your actual API endpoint
    final String libraryId = await getLibraryIdFromSharedPreferences();
    final String? token = await getToken();

    try {
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer $token"
      };
      var response = await http.get(Uri.parse(API.penaltyListing),
          headers: header);

      if (response.statusCode == 200) {
        Map<String, dynamic> result = jsonDecode(response.body);

        // Check if 'data' is a Map and contains 'aaData' key
        if (result['status'] == 'success' &&
            result['data'] is Map &&
            result['data']['aaData'] is List) {
          List<dynamic> aaDataList = result['data']['aaData'];
          List<PenaltyModel> penaltyRecords = [];

          // Iterate through the 'aaData' list
          for (var penaltyData in aaDataList) {
            // Create a BookingModel instance from each penalty data and add it to the list
            penaltyRecords.add(PenaltyModel.fromJson(penaltyData));
          }

          return penaltyRecords;
        } else {
          print('Error: "status" is not "success" or "aaData" is not a List');
          return [];
        }
      } else {
        print('Error statusCode: ${response.statusCode}, Reason: ${response.reasonPhrase}');
        return [];
      }
    } catch (error) {
      print('Error: $error');
      return [];
    }
  }
}

class _PenaltyListScreenState extends State<PenaltyListScreen> {
  late Future<List<PenaltyModel>> penaltyList;
  late List<PenaltyModel> results;

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  Future<void> fetchData() async {
    penaltyList = FetchBooking().fetchPenaltyRecords();
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          title: Text('Penalty Records'),
          leading: IconButton(
            icon: const Icon(Icons.arrow_back),
            onPressed: () {
              Navigator.pushReplacement(
                context,
                MaterialPageRoute(
                  builder: (context) => HomePage(libraryId: ''),
                ),
              );
            },
          ),
        ),
        body: Column(
          children: [
            Expanded(
              child: FutureBuilder<List<PenaltyModel>>(
                future: penaltyList,
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return Center(child: CircularProgressIndicator());
                  } else if (snapshot.hasError) {
                    return Center(
                      child: Text('Error: ${snapshot.error}'),
                    );
                  } else {
                    results = snapshot.data!;
                    return GestureDetector(
                      child: ListView.builder(
                        itemCount: results.length,
                        itemBuilder: (context, index) {
                          var penalty = results[index];
                          return Card(
                            child: SizedBox(
                              height: 100.0,
                              child: ListTile(
                                title: Row(
                                  children: [
                                    SizedBox(width: 32),
                                    Column(
                                      mainAxisAlignment: MainAxisAlignment.center,
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          'Penalty for: ${penalty.itemName}',
                                          style: TextStyle(
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                                onTap: () {
                                  // Handle the tap for individual ListTiles
                                  var selectedPenalty = results[index];
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) => PenaltyDetailPage(penalty: selectedPenalty),
                                    ),
                                  );
                                },
                              ),
                            ),
                          );
                        },
                      ),
                    );
                  }
                },
              ),
            ),
          ],
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

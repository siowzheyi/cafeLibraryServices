import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';
import '../Model/table_model.dart';
import '../Welcome/select_library.dart';


void main(){
  runApp(TableListing());
}

class TableListing extends StatelessWidget {
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
              return TableListScreen(libraryId: libraryId);
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

class TableListScreen extends StatefulWidget {
  final String libraryId;
  final Map<String, String>? headers;

  const TableListScreen({Key? key, required this.libraryId, this.headers}) : super(key: key);

  @override
  _TableListScreenState createState() => _TableListScreenState();
}

class _TableListScreenState extends State<TableListScreen> {
  late Future<List<TableModel>> tables;

  Future<List<TableModel>> getTableList() async {
    try {
      final String libraryId = await getLibraryIdFromSharedPreferences();
      final String? token = await getToken();

      var url = Uri.parse('${API.table}?library_id=$libraryId');
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${token}"
      };
      var response = await http.get(
          url,
          headers: header
      );

      if(response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);
          List<dynamic> aaDataList = result['data']['aaData'];
          List<TableModel> tables = aaDataList
              .map((data) => TableModel.fromJson(data))
              .toList();
          return tables;
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
      print('Error fetching tables: $error');
      return [];
    }
  }

  late Future<List<TableModel>> tableList;

  @override
  void initState() {
    super.initState();
    tableList = getTableList();
    fetchData();
  }

  Future<void> fetchData() async {
    if (mounted) {
      await getTableList();
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
          title: Text('Table Listing'),
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
        body: FutureBuilder<List<TableModel>>(
          future: tableList,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(
                child: Text('Error: ${snapshot.error}'),
              );
            } else {
              List<TableModel> results = snapshot.data!;
              return Container(
                child: ListView.builder(
                  itemCount: results.length,
                  itemBuilder: (context, index) {
                    return Card(
                      child: SizedBox(
                        height: 100.0,
                        child: ListTile(
                          onTap: () {
                            // Navigator.push(
                            //   context,
                            //   MaterialPageRoute(
                            //     builder: (context) => EquipmentDetailsScreen(
                            //       name: results[index].name,
                            //       picture: results[index].picture,
                            //     ),
                            //   ),
                            // );
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
                                  child: Text(
                                    '${index + 1}',
                                    style: TextStyle(
                                      fontSize: 20,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.white,
                                    ),
                                  ),
                                ),
                              ),
                              SizedBox(width: 32),
                              Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    '${results[index].tableNo}',
                                    style: TextStyle(
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
                ),
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
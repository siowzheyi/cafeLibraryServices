import 'package:cafe_library_services/Room/room_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';
import '../Model/room_model.dart';
import '../Welcome/select_library.dart';


void main(){
  runApp(RoomListing());
}

class RoomListing extends StatelessWidget {
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
              return RoomListScreen(libraryId: libraryId);
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

class RoomListScreen extends StatefulWidget {
  final String libraryId;
  final Map<String, String>? headers;

  const RoomListScreen({Key? key, required this.libraryId, this.headers}) : super(key: key);

  @override
  _RoomListScreenState createState() => _RoomListScreenState();
}

class _RoomListScreenState extends State<RoomListScreen> {
  late Future<List<RoomModel>> rooms;

  Future<List<RoomModel>> getRoomList() async {
    try {
      final String libraryId = await getLibraryIdFromSharedPreferences();
      final String? token = await getToken();

      var url = Uri.parse('${API.room}?library_id=$libraryId');
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${token}"
      };
      var response = await http.get(
        url,
        headers: header,
      );

      if (response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);

          // Check if 'aaData' is a List
          if (result['data']['aaData'] is List) {
            List<dynamic> aaDataList = result['data']['aaData'];
            List<RoomModel> rooms = [];

            // Iterate through the 'aaData' list
            for (var aaData in aaDataList) {
              // Check if 'rooms' is a List
              if (aaData['rooms'] is List) {
                List<dynamic> roomsList = aaData['rooms'];

                // Iterate through the 'rooms' list
                for (var roomData in roomsList) {
                  // Create a RoomModel instance from each room data and add it to the list
                  rooms.add(RoomModel.fromJson(roomData));
                }
              }
            }

            return rooms;
          } else {
            print('Error: "aaData" is not a List');
            return [];
          }
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
      print('Error fetching rooms: $error');
      return [];
    }
  }

  late Future<List<RoomModel>> roomList;

  @override
  void initState() {
    super.initState();
    roomList = getRoomList();
    fetchData();
  }

  Future<void> fetchData() async {
    if (mounted) {
      await getRoomList();
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
          title: Text('Room Listing'),
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
          actions: [
            IconButton(
              icon: const Icon(Icons.search),
              onPressed: () {},
            ),
          ],
        ),
        body: FutureBuilder<List<RoomModel>>(
          future: roomList,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(
                child: Text('Error: ${snapshot.error}'),
              );
            } else {
              List<RoomModel> results = snapshot.data!;
              return Container(
                child: ListView.builder(
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
                                builder: (context) => RoomDetailsScreen(
                                  id: results[index].id,
                                  roomNo: results[index].roomNo,
                                  picture: results[index].picture,
                                  type: results[index].type,
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
                                    '${results[index].picture}',
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
                              SizedBox(width: 32),
                              Column(
                                children: [
                                  Text(
                                    '${results[index].roomNo}',
                                    style: TextStyle(
                                        fontWeight: FontWeight.bold) )
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
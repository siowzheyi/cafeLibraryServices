import 'package:cafe_library_services/Room/room_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';

void main() {
  runApp(RoomListing());
}

class RoomListing extends StatelessWidget {
  const RoomListing({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Room Listing',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: RoomListScreen(),
    );
  }
}

class RoomListScreen extends StatefulWidget {
  @override
  _RoomListScreenState createState() => _RoomListScreenState();
}

class _RoomListScreenState extends State<RoomListScreen> {

  List<Room> rooms = [];

  Future<void> getRooms() async {
    try {
      var response = await http.get(Uri.parse(API.room));
      if (response.statusCode == 200) {
        List<dynamic> decodedData = jsonDecode(response.body);

        setState(() {
          rooms = decodedData.map((data) => Room(
            data['roomNo'] ?? '',
            data['picture'] ?? '',
            data['type'] ?? ''
          )).toList();
        });

        print(rooms);
      }
    } catch (ex) {
      print("Error :: " + ex.toString());
    }
  }

  List<Room> filteredRooms = [];
  List<Room> searchHistory = [];

  @override
  void initState() {
    getRooms();
    super.initState();
    filteredRooms = List.from(rooms);
  }

  void filterRooms(String query) {
    setState(() {
      filteredRooms = rooms
          .where((room) =>
      room.roomNo.toLowerCase().contains(query.toLowerCase())).toList();
      //update search history based on the user's query
    });
  }

  void addToSearchHistory(Room room) {
    setState(() {
      searchHistory.add(room);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Room Listing'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(context, MaterialPageRoute(builder:
                (context) => HomePage()));
          },
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.search),
            onPressed: () {
              showSearch(
                context: context,
                delegate: RoomSearchDelegate(rooms, addToSearchHistory),
              );
            },
          ),
        ],
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const SizedBox(height: 16.0),
          SingleChildScrollView(
            scrollDirection: Axis.vertical,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                for (var i = 0; i < 5; i++)
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      for (var j = 0; j < 4; j++)
                        if (i * 4 + j < rooms.length)
                          Container(
                            width: 150.0,
                            margin: const EdgeInsets.symmetric(horizontal: 8.0),
                            child: RoomListItem(room: rooms[i * 4 + j]),
                          )
                        else
                          Container(), // Placeholder for empty cells
                    ],
                  ),
              ],
            ),
          ),
          const SizedBox(height: 16.0,),
        ],
      ),
    );
  }
}

class Room {
  final String roomNo;
  final String picture;
  final String type;

  Room(this.roomNo, this.picture, this.type);
}

class RoomListItem extends StatelessWidget {
  final Room room;

  const RoomListItem({Key? key, required this.room}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => RoomDetailsPage(
              roomNo: room.roomNo,
              picture: room.picture,
              type: room.type,
            ),
          ),
        );
      },
      child: Card(
        margin: const EdgeInsets.symmetric(horizontal: 8.0),
        child: SizedBox(
          width: 150.0, // Adjust the width based on your preference
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Replace this with your room image
              Image.network(
                room.picture,
                width: double.infinity,
                height: 150.0,
                fit: BoxFit.cover,
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      room.roomNo,
                      style: const TextStyle(fontSize: 14.0, fontWeight:
                      FontWeight.bold),
                    ),
                    Text(
                      room.type,
                      style: const TextStyle(fontSize: 14.0),
                    ),
                    const SizedBox(height: 8.0),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class RoomSearchDelegate extends SearchDelegate<String> {
  final List<Room> rooms;
  final Function(Room) addToSearchHistory;

  RoomSearchDelegate(this.rooms, this.addToSearchHistory);

  @override
  List<Widget> buildActions(BuildContext context) {
    return [
      IconButton(
        icon: const Icon(Icons.clear),
        onPressed: () {
          query = '';
        },
      ),
    ];
  }

  @override
  Widget buildLeading(BuildContext context) {
    return IconButton(
      icon: AnimatedIcon(
        icon: AnimatedIcons.menu_arrow,
        progress: transitionAnimation,
      ),
      onPressed: () {
        close(context, '');
      },
    );
  }

  @override
  Widget buildResults(BuildContext context) {
    return buildSuggestions(context);
  }

  @override
  Widget buildSuggestions(BuildContext context) {
    final suggestionList = query.isEmpty
        ? rooms
        : rooms
        .where((room) =>
    room.type.toLowerCase().contains(query.toLowerCase()))
        .toList();

    return ListView.builder(
      itemCount: suggestionList.length,
      itemBuilder: (context, index) {
        return ListTile(
          title: Text(suggestionList[index].type),
          onTap: () {
            // Add the selected room to the search history
            addToSearchHistory(suggestionList[index]);

            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => RoomDetailsPage(
                  roomNo: suggestionList[index].roomNo,
                  picture: suggestionList[index].picture,
                  type: suggestionList[index].type,
                  // Pass more details as needed
                ),
              ),
            );
          },
        );
      },
    );
  }
}

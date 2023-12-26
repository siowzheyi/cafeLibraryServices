import 'package:cafe_library_services/Room/room_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';

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
  final List<Room> rooms = [
    Room('Discussion Room 1', 'assets/discussion_room.jpg', false),
    Room('Discussion Room 2', 'assets/discussion_room.jpg', true),
    Room('Meeting Room', 'assets/meeting_room.jpg', true),
    Room('Carrel Room 1', 'assets/carrel_room.jpg', true),
    Room('Carrel Room 2', 'assets/carrel_room.jpg', true),
    Room('Carrel Room 3', 'assets/carrel_room.jpg', true),
    Room('Carrel Room 4', 'assets/carrel_room.jpg', false),
    Room('Presentation Room', 'assets/presentation_room.jpg', false),
  ];

  List<Room> filteredRooms = [];
  List<Room> searchHistory = [];

  @override
  void initState() {
    super.initState();
    filteredRooms = List.from(rooms);
    //searchHistory = List.from(searchHistory);
  }

  void filterRooms(String query) {
    setState(() {
      filteredRooms = rooms
          .where((room) =>
      room.name.toLowerCase().contains(query.toLowerCase())).toList();
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
        title: Text('Room Listing'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => HomePage()));
          },
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.search),
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
          SizedBox(height: 16.0),
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
                            margin: EdgeInsets.symmetric(horizontal: 8.0),
                            child: RoomListItem(room: rooms[i * 4 + j]),
                          )
                        else
                          Container(), // Placeholder for empty cells
                    ],
                  ),
              ],
            ),
          ),
          SizedBox(height: 16.0,),
        ],
      ),
    );
  }
}

class Room {
  final String name;
  final String imageUrl;
  bool isAvailable;

  Room(this.name, this.imageUrl, this.isAvailable);
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
              name: room.name,
              imageUrl: room.imageUrl,
              isAvailable: room.isAvailable,
            ),
          ),
        );
      },
      child: Card(
        margin: EdgeInsets.symmetric(horizontal: 8.0),
        child: Container(
          width: 150.0, // Adjust the width based on your preference
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Replace this with your room image
              Image.asset(
                room.imageUrl,
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
                      room.name,
                      style: TextStyle(fontSize: 14.0, fontWeight: FontWeight.bold),
                    ),
                    SizedBox(height: 8.0),
                    Text(
                      room.isAvailable ? 'Available' : 'In used',
                      style: TextStyle(
                        fontSize: 12.0,
                        color: room.isAvailable ? Colors.green : Colors.red,
                      ),
                    ),
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
        icon: Icon(Icons.clear),
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
    room.name.toLowerCase().contains(query.toLowerCase()))
        .toList();

    return ListView.builder(
      itemCount: suggestionList.length,
      itemBuilder: (context, index) {
        return ListTile(
          title: Text(suggestionList[index].name),
          onTap: () {
            // Add the selected room to the search history
            addToSearchHistory(suggestionList[index]);

            // You can navigate to the room details screen or handle the selection as needed
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => RoomDetailsPage(
                  name: suggestionList[index].name,
                  imageUrl: suggestionList[index].imageUrl,
                  isAvailable: suggestionList[index].isAvailable,
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

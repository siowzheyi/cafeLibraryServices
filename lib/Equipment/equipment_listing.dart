import 'package:cafe_library_services/Equipment/equipment_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';

void main(){
  runApp(EquipmentListing());
}

class EquipmentListing extends StatelessWidget {
  const EquipmentListing({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Equipment Listing',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: EquipmentListScreen(),
    );
  }
}

class EquipmentListScreen extends StatefulWidget {
  @override
  _EquipmentListScreenState createState() => _EquipmentListScreenState();
}

class _EquipmentListScreenState extends State<EquipmentListScreen> {
  final List<Equipment> equipment = [
    Equipment('Controller', 'RM 5 per hour', 'assets/controller.jpg', true),
    Equipment('Projector', 'RM 2 per hour', 'assets/projector.jpg', false),
    Equipment('PS4', 'RM 5 per hour', 'assets/ps4.jpg', false),
    Equipment('PS5', 'RM 6 per hour', 'assets/ps5.jpg', true),
    Equipment('Chess', 'RM 2 per hour', 'assets/chess.jpg', true),
    Equipment('Board games', 'RM 2 per hour', 'assets/board_games.jpg', false),
    Equipment('Carom', 'RM 3 per hour', 'assets/carom.jpg', false),
    Equipment('Saidina', 'RM 3 per hour', 'assets/saidina.jpg', true),
  ];

  List<Equipment> filteredEquipment = [];
  List<Equipment> searchHistory = [];

  @override
  void initState() {
    super.initState();
    filteredEquipment = List.from(equipment);
  }

  void filterEquipment(String query) {
    setState(() {
      filteredEquipment = equipment
          .where((equipment) =>
      equipment.name.toLowerCase().contains(query.toLowerCase()) ||
          equipment.price.toLowerCase().contains(query.toLowerCase()))
          .toList();
      //update search history based on the user's query
    });
  }

  void addToSearchHistory(Equipment equipment) {
    setState(() {
      searchHistory.add(equipment);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Equipment Listing'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: (){
            Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => HomePage()));
          },
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.search),
            onPressed: () {
              showSearch(
                context: context,
                delegate: EquipmentSearchDelegate(equipment, addToSearchHistory),
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
                        if (i * 4 + j < equipment.length)
                          Container(
                            width: 150.0,
                            margin: EdgeInsets.symmetric(horizontal: 8.0),
                            child: EquipmentListItem(equipment: equipment[i * 4 + j]),
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

class Equipment {
  final String name;
  final String price;
  final String imageUrl;
  bool isAvailable;

  Equipment(this.name, this.price, this.imageUrl, this.isAvailable);
}

class EquipmentListItem extends StatelessWidget {
  final Equipment equipment;

  const EquipmentListItem({Key? key, required this.equipment}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => EquipmentDetailsPage(
              name: equipment.name,
              price: equipment.price,
              imageUrl: equipment.imageUrl,
              isAvailable: equipment.isAvailable,
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
              Image.asset(
                equipment.imageUrl,
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
                      equipment.name,
                      style: TextStyle(fontSize: 14.0, fontWeight: FontWeight.bold),
                    ),
                    Text(
                      '${equipment.price}',
                      style: TextStyle(fontSize: 12.0, fontStyle: FontStyle.italic),
                    ),
                    SizedBox(height: 8.0),
                    Text(
                      equipment.isAvailable ? 'Available' : 'Checked Out',
                      style: TextStyle(
                        fontSize: 12.0,
                        color: equipment.isAvailable ? Colors.green : Colors.red,
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

class EquipmentSearchDelegate extends SearchDelegate<String> {
  final List<Equipment> equipment;
  final Function(Equipment) addToSearchHistory;

  EquipmentSearchDelegate(this.equipment, this.addToSearchHistory);

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
        ? equipment
        : equipment
        .where((equipment) =>
    equipment.name.toLowerCase().contains(query.toLowerCase()) ||
        equipment.price.toLowerCase().contains(query.toLowerCase()))
        .toList();

    return ListView.builder(
      itemCount: suggestionList.length,
      itemBuilder: (context, index) {
        return ListTile(
          title: Text(suggestionList[index].name),
          onTap: () {
            // Add the selected equipment to the search history
            addToSearchHistory(suggestionList[index]);

            // You can navigate to the equipment details screen or handle the selection as needed
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => EquipmentDetailsPage(
                  name: suggestionList[index].name,
                  price: suggestionList[index].price,
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

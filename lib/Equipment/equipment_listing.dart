import 'package:cafe_library_services/Equipment/equipment_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';


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

  List<Equipment> equipments = [];

  Future<void> getEquipments() async {
    try {
      var response = await http.get(Uri.parse(API.equipment));
      if (response.statusCode == 200) {
        List<dynamic> decodedData = jsonDecode(response.body);

        setState(() {
          equipments = decodedData.map((data) => Equipment(
              data['name'] ?? '',
              data['picture'] ?? ''
          )).toList();
        });

        print(equipments);
      }
    } catch (ex) {
      print("Error :: " + ex.toString());
    }
  }

  List<Equipment> filteredEquipment = [];
  List<Equipment> searchHistory = [];

  @override
  void initState() {
    getEquipments();
    super.initState();
    filteredEquipment = List.from(equipments);
  }

  void filterEquipment(String query) {
    setState(() {
      filteredEquipment = equipments
          .where((equipment) =>
      equipment.name.toLowerCase().contains(query.toLowerCase()))
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
        title: const Text('Equipment Listing'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: (){
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
                delegate: EquipmentSearchDelegate(equipments,
                    addToSearchHistory),
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
                        if (i * 4 + j < equipments.length)
                          Container(
                            width: 150.0,
                            margin: const EdgeInsets.symmetric(horizontal: 8.0),
                            child: EquipmentListItem(equipment:
                            equipments[i * 4 + j]),
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

class Equipment {
  final String name;
  final String picture;

  Equipment(this.name, this.picture);
}

class EquipmentListItem extends StatelessWidget {
  final Equipment equipment;

  const EquipmentListItem({Key? key, required this.equipment}) : super(key:
  key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => EquipmentDetailsPage(
              name: equipment.name,
              picture: equipment.picture,
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
              Image.network(
                equipment.picture,
                width: double.infinity,
                height: 150.0,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) {
                  // Handle image loading error
                  return const Icon(Icons.error);
                },
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      equipment.name,
                      style: const TextStyle(fontSize: 14.0, fontWeight:
                      FontWeight.bold),
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

class EquipmentSearchDelegate extends SearchDelegate<String> {
  final List<Equipment> equipment;
  final Function(Equipment) addToSearchHistory;

  EquipmentSearchDelegate(this.equipment, this.addToSearchHistory);

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
        ? equipment
        : equipment
        .where((equipment) =>
    equipment.name.toLowerCase().contains(query.toLowerCase()))
        .toList();

    return ListView.builder(
      itemCount: suggestionList.length,
      itemBuilder: (context, index) {
        return ListTile(
          title: Text(suggestionList[index].name),
          onTap: () {
            // Add the selected equipment to the search history
            addToSearchHistory(suggestionList[index]);

            // You can navigate to the equipment details screen or handle the
            // selection as needed
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => EquipmentDetailsPage(
                  name: suggestionList[index].name,
                  picture: suggestionList[index].picture,
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

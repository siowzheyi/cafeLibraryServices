import 'package:cafe_library_services/Equipment/equipment_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:cafe_library_services/Equipment/search_history.dart';

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
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Text(
              'Our recommendations!',
              style: TextStyle(
                fontSize: 32.0,
                fontWeight: FontWeight.bold,
                fontFamily: 'Roboto',
              ),
            ),
          ),
          SizedBox(height: 16.0),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: [
                for (var equipmentItem in equipment)
                  Container(
                    width: 150.0,
                    margin: EdgeInsets.symmetric(horizontal: 8.0),
                    child: EquipmentListItem(equipment: equipmentItem),
                  ),
              ],
            ),
          ),
          SizedBox(height: 16.0,),
          SearchHistory(
            searchHistory: searchHistory,
          ),
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

class EquipmentDetailsScreen extends StatelessWidget {
  final Equipment equipment;

  const EquipmentDetailsScreen({Key? key, required this.equipment}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Equipment Details'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.asset(
              equipment.imageUrl,
              width: 200.0,
              height: 300.0,
              fit: BoxFit.cover,
            ),
            SizedBox(height: 16.0),
            Text(
              equipment.name,
              style: TextStyle(fontSize: 20.0, fontWeight: FontWeight.bold),
            ),
            Text(
              'by ${equipment.price}',
              style: TextStyle(fontSize: 16.0, fontStyle: FontStyle.italic),
            ),
            SizedBox(height: 16.0),
            ElevatedButton(
              onPressed: () {
                // Handle equipment borrowing logic
                // For example, show a confirmation dialog
                showDialog(
                  context: context,
                  builder: (context) => AlertDialog(
                    title: Text('Borrow Confirmation'),
                    content: Text('Do you want to borrow ${equipment.name}?'),
                    actions: [
                      TextButton(
                        onPressed: () {
                          // Perform equipment borrowing logic here
                          Navigator.pop(context); // Close the dialog
                          // Optionally show a success message
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text('Equipment borrowed successfully!'),
                            ),
                          );
                        },
                        child: Text('Yes'),
                      ),
                      TextButton(
                        onPressed: () {
                          Navigator.pop(context); // Close the dialog
                        },
                        child: Text('No'),
                      ),
                    ],
                  ),
                );
              },
              child: Text('Borrow Equipment'),
            ),
          ],
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

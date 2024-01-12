import 'package:cafe_library_services/Beverage/add_to_cart.dart';
import 'package:cafe_library_services/Beverage/beverage_details.dart';
import 'package:cafe_library_services/Beverage/choose_table.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';

void main() {
  runApp(BeverageListing());
}

class BeverageListing extends StatelessWidget {
  const BeverageListing({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Beverage Listing',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: BeverageListScreen(),
    );
  }
}

class BeverageListScreen extends StatefulWidget {
  @override
  _BeverageListScreenState createState() => _BeverageListScreenState();
}

class _BeverageListScreenState extends State<BeverageListScreen> {

  List<Beverage> beverages = [];

  Future<void> getBeverages() async {
    try {
      var response = await http.get(Uri.parse(API.beverage));
      if (response.statusCode == 200) {
        List<dynamic> decodedData = jsonDecode(response.body);

        setState(() {
          beverages = decodedData.map((data) => Beverage(
            data['name'] ?? '',
            data['category'] ?? '',
            data['price'] ?? '',
            data['picture'] ?? ''
          )).toList();
        });

        print(beverages);
      }
    } catch (ex) {
      print("Error :: " + ex.toString());
    }
  }

  List<Beverage> filteredBeverages = [];
  List<Beverage> searchHistory = [];

  @override
  void initState() {
    getBeverages();
    super.initState();
    filteredBeverages = List.from(beverages);
  }

  void filterBeverages(String query) {
    setState(() {
      filteredBeverages = beverages
          .where((beverage) =>
      beverage.name.toLowerCase().contains(query.toLowerCase()))
          .toList();
      //update search history based on the user's query
    });
  }

  void addToSearchHistory(Beverage beverage) {
    setState(() {
      searchHistory.add(beverage);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Beverage Listing'),
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
                delegate: BeverageSearchDelegate(beverages, addToSearchHistory),
              );
            },
          ),
        ],
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Padding(
            padding: EdgeInsets.all(16.0),
            child: Text(
              'What would you like to order for today?',
              style: TextStyle(
                fontSize: 32.0,
                fontWeight: FontWeight.bold,
                fontFamily: 'Roboto',
              ),
            ),
          ),
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
                        if (i * 4 + j < beverages.length)
                          Container(
                            width: 150.0,
                            margin: const EdgeInsets.symmetric(horizontal: 8.0),
                            child: BeverageListItem(beverage:
                            beverages[i * 4 + j]),
                          )
                        else
                          Container(), // Placeholder for empty cells
                    ],
                  ),
              ],
            ),
          ),
          const SizedBox(height: 16.0,),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Row(
              children: [
                ElevatedButton(
                  child: const Text('Start ordering!'),
                  onPressed: () {
                    // go to order beverage page
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => BeverageOrderPage(),
                      ),
                    );
                  },
                ),
                const SizedBox(width: 16.0,),
                ElevatedButton(
                  child: const Text('View table'),
                  onPressed: () {
                    // go to choose table
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => TableSelectionPage(),
                      ),
                    );
                  },
                ),
                const SizedBox(width: 16.0,),
                ElevatedButton(
                  child: const Text('Add to cart'),
                  onPressed: () {
                    // go to order beverage page
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => BeverageOrderPage(),
                      ),
                    );
                  },
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

class Beverage {
  final String name;
  final String category;
  final String price;
  final String picture;

  Beverage(this.name, this.category, this.price, this.picture);
}

class BeverageListItem extends StatelessWidget {
  final Beverage beverage;

  const BeverageListItem({Key? key, required this.beverage}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => BeverageDetailsPage(
              name: beverage.name,
              category: beverage.category,
              price: beverage.price,
              picture: beverage.picture,
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
                beverage.picture,
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
                      beverage.name,
                      style: const TextStyle(fontSize: 14.0, fontWeight:
                      FontWeight.bold),
                    ),
                    Text(
                      'RM${beverage.price}',
                      style: const TextStyle(fontSize: 12.0, fontStyle:
                      FontStyle.italic),
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

class BeverageSearchDelegate extends SearchDelegate<String> {
  final List<Beverage> beverages;
  final Function(Beverage) addToSearchHistory;

  BeverageSearchDelegate(this.beverages, this.addToSearchHistory);

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
        ? beverages
        : beverages
        .where((beverage) =>
    beverage.name.toLowerCase().contains(query.toLowerCase()) ||
        beverage.price.toLowerCase().contains(query.toLowerCase()))
        .toList();

    return ListView.builder(
      itemCount: suggestionList.length,
      itemBuilder: (context, index) {
        return ListTile(
          title: Text(suggestionList[index].name),
          onTap: () {
            // Add the selected beverage to the search history
            addToSearchHistory(suggestionList[index]);

            // You can navigate to the beverage details screen or handle the
            // selection as needed
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => BeverageDetailsPage(
                  name: suggestionList[index].name,
                  category: suggestionList[index].category,
                  price: suggestionList[index].price,
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

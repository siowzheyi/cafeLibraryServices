import 'package:cafe_library_services/Beverage/beverage_details.dart';
import 'package:cafe_library_services/Beverage/choose_table.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:cafe_library_services/Beverage/search_history.dart';

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
  final List<Beverage> beverages = [
    Beverage('Hot Coffee Latte', 'RM8.50', 'One of the best beverage, Hot Coffee', 'assets/hot_coffee_latte.png', true),
    Beverage('Mocha Iced Coffee', 'RM9.20', 'One of the best beverage, Ice Coffee', 'assets/mocha_iced_coffee.jpg', true),
    Beverage('Java Chip Frappuccino', 'RM14.30', 'One of the best beverage, Frappe', 'assets/java_chip_frappucino.jpg', false),
    Beverage('Strawberry Smoothies', 'RM11.20', 'One of the best beverage, Smoothies', 'assets/strawberry_smoothie.jpg', true),
    Beverage('Red Velvet Cake', 'RM7.50', 'One of the best pastry, Cake', 'assets/red_velvet_cake.jpg', false),
    Beverage('Croissant French Toast', 'RM8.30', 'One of the best pastry, Bread', 'assets/croissant_french_toast.jpg', false),
  ];

  List<Beverage> filteredBeverages = [];
  List<Beverage> searchHistory = [];

  @override
  void initState() {
    super.initState();
    filteredBeverages = List.from(beverages);
    //searchHistory = List.from(searchHistory);
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
        title: Text('Beverage Listing'),
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
                delegate: BeverageSearchDelegate(beverages, addToSearchHistory),
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
                for (var beverage in beverages)
                  Container(
                    width: 150.0,
                    margin: EdgeInsets.symmetric(horizontal: 8.0),
                    child: BeverageListItem(beverage: beverage),
                  ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Row(
              children: [
                ElevatedButton(
                  child: Text('Start ordering!'),
                  onPressed: () {
                    // choose table -> choose beverage

                  },
                ),
                SizedBox(width: 16.0,),
                ElevatedButton(
                  child: Text('View table'),
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
                SizedBox(width: 16.0,),
                ElevatedButton(
                  child: Text('Add to cart'),
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

class Beverage {
  final String name;
  final String price;
  final String description;
  final String imageUrl;
  bool isAvailable;

  Beverage(this.name, this.price, this.description, this.imageUrl, this.isAvailable);
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
              price: beverage.price,
              description: beverage.description,
              imageUrl: beverage.imageUrl,
              isAvailable: beverage.isAvailable,
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
                beverage.imageUrl,
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
                      style: TextStyle(fontSize: 14.0, fontWeight: FontWeight.bold),
                    ),
                    Text(
                      '${beverage.price}',
                      style: TextStyle(fontSize: 12.0, fontStyle: FontStyle.italic),
                    ),
                    SizedBox(height: 8.0),
                    Text(
                      beverage.isAvailable ? 'Available' : 'Out of stock',
                      style: TextStyle(
                        fontSize: 12.0,
                        color: beverage.isAvailable ? Colors.green : Colors.red,
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

class BeverageSearchDelegate extends SearchDelegate<String> {
  final List<Beverage> beverages;
  final Function(Beverage) addToSearchHistory;

  BeverageSearchDelegate(this.beverages, this.addToSearchHistory);

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

            // You can navigate to the beverage details screen or handle the selection as needed
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => BeverageDetailsPage(
                  name: suggestionList[index].name,
                  price: suggestionList[index].price,
                  description: suggestionList[index].description,
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

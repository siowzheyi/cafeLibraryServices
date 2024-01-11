import 'package:cafe_library_services/Beverage/add_to_cart.dart';
import 'package:cafe_library_services/Beverage/beverage_details.dart';
import 'package:cafe_library_services/Beverage/choose_table.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';

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
    Beverage('Apple Pie', 'RM6.50', 'One of the best pastry, Apple Pie', 'assets/apple_pie.jpg', true),
    Beverage('Cendol', 'RM5.00', 'Malaysian favourite, cendol', 'assets/cendol.jpg', true),
    Beverage('Ais Kacang', 'RM14.30', 'Get one in this heat!', 'assets/ais_kacang.jpg', false),
    Beverage('Kuih Lapis', 'RM3.40', 'Colorful sweetness', 'assets/kuih_lapis.jpg', true),
    Beverage('Pineapple Tart', 'RM1.00', 'Hari Raya mood, anyone?', 'assets/pineapple_tart.jpg', false),
    Beverage('Bubur Cha Cha', 'RM6.50', 'Cha cha cha!', 'assets/chacha.jpg', false),
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
  final String price;
  final String description;
  final String imageUrl;
  bool isAvailable;

  Beverage(this.name, this.price, this.description, this.imageUrl, this
      .isAvailable);
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
        margin: const EdgeInsets.symmetric(horizontal: 8.0),
        child: SizedBox(
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
                      style: const TextStyle(fontSize: 14.0, fontWeight:
                      FontWeight.bold),
                    ),
                    Text(
                      beverage.price,
                      style: const TextStyle(fontSize: 12.0, fontStyle:
                      FontStyle.italic),
                    ),
                    const SizedBox(height: 8.0),
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

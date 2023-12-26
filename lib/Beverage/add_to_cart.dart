import 'package:flutter/material.dart';
import 'beverage_listing.dart';
import 'order_summary.dart';

void main() {
  runApp(
    MaterialApp(
      title: 'Order Beverages',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: BeverageOrderPage(),
    ),
  );
}

class Beverage {
  final String name;
  final double price;
  final String imageUrl;
  bool isAvailable;
  int quantity;

  Beverage({
    required this.name,
    required this.price,
    required this.imageUrl,
    this.isAvailable = true,
    this.quantity = 1, // Default quantity is 1
  });
}

class BeverageOrderPage extends StatefulWidget {
  @override
  _BeverageOrderPageState createState() => _BeverageOrderPageState();
}

class _BeverageOrderPageState extends State<BeverageOrderPage> {
  List<Beverage> availableBeverages = [
    Beverage(name: 'Hot Coffee Latte', price: 8.50, imageUrl: 'assets/hot_coffee_latte.png'),
    Beverage(name: 'Mocha Iced Coffee', price: 9.20, imageUrl: 'assets/mocha_iced_coffee.jpg'),
    Beverage(name: 'Java Chip Frappuccino', price: 14.30, imageUrl: 'assets/java_chip_frappucino.jpg', isAvailable: false),
    Beverage(name: 'Strawberry Smoothies', price: 11.20, imageUrl: 'assets/strawberry_smoothie.jpg'),
    Beverage(name: 'Red Velvet Cake', price: 7.50, imageUrl: 'assets/red_velvet_cake.jpg', isAvailable: false),
    Beverage(name: 'Croissant French Toast', price: 8.30, imageUrl: 'assets/croissant_french_toast.jpg', isAvailable: false),
    Beverage(name: 'Apple Pie', price: 6.50, imageUrl: 'assets/apple_pie.jpg'),
    Beverage(name: 'Cendol', price: 5.00, imageUrl: 'assets/cendol.jpg'),
    Beverage(name: 'Ais Kacang', price: 14.30, imageUrl: 'assets/ais_kacang.jpg', isAvailable: false),
    Beverage(name: 'Kuih Lapis', price: 3.40, imageUrl: 'assets/kuih_lapis.jpg'),
    Beverage(name: 'Pineapple Tart', price: 1.00, imageUrl: 'assets/pineapple_tart.jpg', isAvailable: false),
    Beverage(name: 'Bubur Cha Cha', price: 6.50, imageUrl: 'assets/chacha.jpg', isAvailable: false),
  ];

  List<Beverage> selectedBeverages = [];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Order Beverages'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => BeverageListing()));
          },
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.shopping_cart),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => OrderSummaryPage(selectedBeverages: selectedBeverages),
                ),
              );
            },
          ),
        ],
      ),
      body: GridView.builder(
        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 2,
          crossAxisSpacing: 8.0,
          mainAxisSpacing: 8.0,
        ),
        itemCount: availableBeverages.length,
        itemBuilder: (context, index) {
          Beverage beverage = availableBeverages[index];
          return GestureDetector(
            onTap: () {
              // Check if the beverage is available before toggling
              if (!beverage.isAvailable) {
                return; // Do nothing for unavailable beverages
              }

              // Toggle the selection of the beverage
              setState(() {
                if (selectedBeverages.contains(beverage)) {
                  selectedBeverages.remove(beverage);
                } else {
                  selectedBeverages.add(beverage);
                }
              });
            },
            child: Card(
              elevation: 2.0,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Image.asset(
                    beverage.imageUrl,
                    height: 100.0,
                    width: 100.0,
                    fit: BoxFit.cover,
                  ),
                  SizedBox(height: 8.0),
                  Text(
                    beverage.name,
                    style: TextStyle(fontSize: 16.0, fontWeight: FontWeight.bold),
                  ),
                  Text('RM${beverage.price.toStringAsFixed(2)}'),
                  SizedBox(height: 2.0),
                  Text(
                    beverage.isAvailable
                        ? selectedBeverages.contains(beverage) ? 'Selected' : 'Tap to select'
                        : 'Out of Stock',
                    style: TextStyle(
                      color: beverage.isAvailable ? Colors.green : Colors.red,
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}



import 'package:flutter/material.dart';

void main() {
  runApp(
    MaterialApp(
      title: 'Order Beverage',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: BeverageOrderPage(selectedTable: 0),
    ),
  );
}

class Beverage {
  final String name;
  final String price;
  final String description;
  final String imageUrl;
  bool isAvailable;

  Beverage(this.name, this.price, this.description, this.imageUrl, this.isAvailable);
}

class BeverageOrderPage extends StatelessWidget {
  final int selectedTable;

  final List<Beverage> beverages = [
    Beverage('Hot Coffee Latte', 'RM8.50', 'One of the best beverage, Hot Coffee', 'assets/hot_coffee_latte.png', true),
    Beverage('Mocha Iced Coffee', 'RM9.20', 'One of the best beverage, Ice Coffee', 'assets/mocha_iced_coffee.jpg', true),
    Beverage('Java Chip Frappuccino', 'RM14.30', 'One of the best beverage, Frappe', 'assets/java_chip_frappucino.jpg', false),
    Beverage('Strawberry Smoothies', 'RM11.20', 'One of the best beverage, Smoothies', 'assets/strawberry_smoothie.jpg', true),
    Beverage('Red Velvet Cake', 'RM7.50', 'One of the best pastry, Cake', 'assets/red_velvet_cake.jpg', false),
    Beverage('Croissant French Toast', 'RM8.30', 'One of the best pastry, Bread', 'assets/croissant_french_toast.jpg', false),
  ];

  BeverageOrderPage({required this.selectedTable});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Order Beverages'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Text(
              'Selected Table: $selectedTable',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
          ),
          Expanded(
            child: GridView.builder(
              gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2, // Number of columns in the grid
                crossAxisSpacing: 8.0,
                mainAxisSpacing: 8.0,
              ),
              itemCount: beverages.length,
              itemBuilder: (context, index) {
                return GestureDetector(
                  onTap: () {
                    // Add logic to handle beverage selection
                    // You can navigate to another page or perform any other action
                    // For simplicity, show a snackbar with the selected beverage name
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(
                        content: Text('Selected Beverage: ${beverages[index].name}'),
                      ),
                    );
                  },
                  child: Card(
                    elevation: 2.0,
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Image.asset(
                          beverages[index].imageUrl,
                          height: 100.0,
                          width: 100.0,
                          fit: BoxFit.cover,
                        ),
                        SizedBox(height: 8.0),
                        Text(
                          beverages[index].name,
                          style: TextStyle(fontSize: 16.0, fontWeight: FontWeight.bold),
                        ),
                        Text('RM${beverages[index].price}'),
                        SizedBox(height: 2.0),
                        ElevatedButton(
                          child: Text('Order'),
                          onPressed: () {

                          },
                        )
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

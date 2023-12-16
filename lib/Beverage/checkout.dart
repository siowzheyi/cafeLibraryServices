import 'package:flutter/material.dart';

import 'add_to_cart.dart';

class CheckoutPage extends StatefulWidget {
  final List<Beverage> selectedBeverages;

  CheckoutPage({required this.selectedBeverages});

  @override
  _CheckoutPageState createState() => _CheckoutPageState();
}

class _CheckoutPageState extends State<CheckoutPage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Checkout'),
      ),
      body: ListView.builder(
        itemCount: widget.selectedBeverages.length,
        itemBuilder: (context, index) {
          Beverage beverage = widget.selectedBeverages[index];
          return ListTile(
            title: Text(beverage.name),
            subtitle: Text('RM${beverage.price.toStringAsFixed(2)}'),
            leading: Image.asset(
              beverage.imageUrl,
              height: 40.0,
              width: 40.0,
              fit: BoxFit.cover,
            ),
          );
        },
      ),
      bottomNavigationBar: Container(
        padding: EdgeInsets.all(16.0),
        child: ElevatedButton(
          onPressed: () {
            // Add your checkout logic here
            // For simplicity, just navigate back to the beverage order page

          },
          child: Text('Proceed to Checkout'),
        ),
      ),
    );
  }
}

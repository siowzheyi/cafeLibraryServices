import 'package:cafe_library_services/Beverage/order_summary.dart';
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
    // Calculate total price
    double totalPrice = 0.0;
    for (Beverage beverage in widget.selectedBeverages) {
      totalPrice += beverage.price * beverage.quantity;
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Checkout'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(
                builder: (context) => OrderSummaryPage(selectedBeverages:
                widget.selectedBeverages),
              ),
            );
          },
        ),
      ),
      body: Column(
        children: [
          Container(
            color: Colors.grey.shade200,
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Receipt',
                  style: TextStyle(fontSize: 20.0, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 8.0),
                ListView.builder(
                  shrinkWrap: true,
                  itemCount: widget.selectedBeverages.length,
                  itemBuilder: (context, index) {
                    Beverage beverage = widget.selectedBeverages[index];
                    return ListTile(
                      title: Text(beverage.name),
                      subtitle: Text('RM${beverage.price.toStringAsFixed(2)}'),
                      trailing: Text('Quantity: ${beverage.quantity}'),
                    );
                  },
                ),
              ],
            ),
          ),
          const SizedBox(height: 16.0),
          Card(
            color: Colors.green,
            elevation: 4.0,
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Text(
                'Total Price: RM${totalPrice.toStringAsFixed(2)}',
                style: const TextStyle(fontSize: 18.0, color: Colors.white),
              ),
            ),
          ),
          const SizedBox(height: 16.0),
          ElevatedButton(
            onPressed: () {
              // Show an AlertDialog with the message
              showDialog(
                context: context,
                builder: (BuildContext context) {
                  return AlertDialog(
                    title: const Text('Order Accepted'),
                    content: const Text('Your order is accepted and will be '
                        'processed!'),
                    actions: <Widget>[
                      TextButton(
                        onPressed: () {
                          Navigator.of(context).pop(); // Close the dialog
                          // Perform any additional actions if needed

                          // Example: Navigate to another page
                          Navigator.pushReplacement(
                            context,
                            MaterialPageRoute(
                              builder: (context) => BeverageOrderPage(),
                            ),
                          );
                        },
                        child: const Text('OK'),
                      ),
                    ],

                  );
                },
              );
            },
            child: const Text('Proceed to Checkout'),
          ),
        ],
      ),
    );
  }
}

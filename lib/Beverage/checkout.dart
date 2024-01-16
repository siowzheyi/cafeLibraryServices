import 'package:flutter/material.dart';
import '../Model/beverage_model.dart';

class CheckoutScreen extends StatelessWidget {
  final List<BeverageModel> selectedItems;
  final List<int> quantity;
  final double totalPrice; // Add totalPrice as a parameter

  const CheckoutScreen({
    Key? key,
    required this.selectedItems,
    required this.quantity,
    required this.totalPrice,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Checkout'),
      ),
      body: ListView.builder(
        itemCount: selectedItems.length,
        itemBuilder: (context, index) {
          double itemTotal = double.parse(selectedItems[index].price) * quantity[index];
          return SingleChildScrollView(
            child: SizedBox(
              height: 80.0,
              child: Card(
                child: ListTile(
                  title: Text(selectedItems[index].name),
                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Quantity: ${quantity[index]}'),
                      Text('Total Price: RM${itemTotal.toStringAsFixed(2)}'),
                    ],
                  ),
                ),
              ),
            ),
          );
        },
      ),
      bottomNavigationBar: BottomAppBar(
        child: Container(
          padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Display total price for all items
              Text('Grand Total Price: RM${totalPrice.toStringAsFixed(2)}'),
              SizedBox(height: 50.0),
              ElevatedButton(
                onPressed: () {
                  // Perform the payment logic here
                  // You can show a loading screen or perform any necessary actions
                  // after the user clicks the "Pay" button
                  _handlePayment(context);
                },
                child: Text('Pay'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _handlePayment(BuildContext context) {
    // Show a loading screen or perform payment logic here
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              CircularProgressIndicator(),
              SizedBox(height: 16.0),
              Text('Processing payment...'),
            ],
          ),
        );
      },
    );

    // Simulate a delay for demonstration purposes
    Future.delayed(Duration(seconds: 2), () {
      // Close the loading screen
      Navigator.of(context).pop();

      // You can navigate to a success page or perform any other action
      // after the payment is complete
      // Example: Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => PaymentSuccessScreen()));
    });
  }
}

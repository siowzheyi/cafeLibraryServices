import 'package:flutter/material.dart';
import 'booking_record.dart';

class PenaltyPage extends StatelessWidget {
  final List<BookingRecord> itemsWithPenalties;

  // Make itemsWithPenalties optional by providing a default empty list
  PenaltyPage({List<BookingRecord>? itemsWithPenalties}) : itemsWithPenalties
  = itemsWithPenalties ?? [];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Penalty Page'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(8.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Items with Penalties:',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Expanded(
              child: ListView.builder(
                itemCount: itemsWithPenalties.length,
                itemBuilder: (context, index) {
                  return Padding(
                    padding: const EdgeInsets.symmetric(vertical: 8.0),
                    child: Card(
                      elevation: 3,
                      child: ListTile(
                        title: Text(itemsWithPenalties[index].itemName),
                        subtitle: Text('Category: ${itemsWithPenalties[index]
                            .category.toString().split('.').last}'),
                        // Add a 'Pay' button to each ListTile
                        trailing: ElevatedButton(
                          onPressed: () {
                            // Handle the 'Pay' button press

                            // You can navigate to a payment screen or perform
                            // other actions
                            _handlePayButtonPress(context, itemsWithPenalties
                            [index]);
                          },
                          child: const Text('Pay'),
                        ),
                      ),
                    ),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Function to handle the 'Pay' button press
  void _handlePayButtonPress(BuildContext context, BookingRecord bookingRecord)
  {
    TextEditingController paymentController = TextEditingController();

    // Add your logic here to handle the payment calculation

    // For demonstration purposes, this example calculates a penalty based on
    // the number of days overdue
    DateTime borrowedDate = DateTime.parse(bookingRecord.borrowedDate);
    DateTime currentDate = DateTime.now();
    int daysDifference = currentDate.difference(borrowedDate).inDays;

    double penaltyRate = 0.5; // Adjust the penalty rate as needed
    double penaltyAmount = daysDifference * penaltyRate;

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Payment Details'),
          content: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text('Item: ${bookingRecord.itemName}'),
              Text('Penalty Rate: RM${penaltyRate.toStringAsFixed(2)} per day'),
              Text('Days Overdue: $daysDifference'),
              Text('Total Penalty Amount: RM${penaltyAmount.toStringAsFixed(
                  2)}'),
              const SizedBox(height: 16),
              const Text('Enter Payment Amount:'),
              TextField(
                controller: paymentController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(
                  hintText: 'Enter amount',
                ),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () {
                // Validate and process the user input
                String paymentText = paymentController.text;
                if (paymentText.isNotEmpty) {
                  double userPayment = double.tryParse(paymentText) ?? 0.0;

                  // Check if the user's payment is sufficient
                  if (userPayment == penaltyAmount) {
                    // Perform additional actions here (e.g., submit payment)
                    // You can use the user's input in your payment logic
                    print('User Payment: $userPayment');

                    // Close the dialog
                    Navigator.of(context).pop();
                  } else {
                    // Show an error message if the payment is not sufficient
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text('Please enter exactly the amount of '
                            'the penalty.'),
                      ),
                    );
                  }
                } else {
                  // Show an error message if the input is empty
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('Please enter a valid amount.'),
                    ),
                  );
                }
              },
              child: const Text('OK'),
            ),
          ],
        );
      },
    );
  }
}

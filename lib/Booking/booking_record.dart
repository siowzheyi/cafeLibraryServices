import 'package:flutter/material.dart';
import '../Model/booking_model.dart';

class BookingDetailPage extends StatelessWidget {
  final BookingModel booking;

  const BookingDetailPage({Key? key, required this.booking}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Booking Details'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Card(
          elevation: 8, // Set elevation for the card
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Booking No: ${booking.itemName}', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                SizedBox(height: 10),
                Text('Booking: ${booking.itemId}'),
                SizedBox(height: 10),
                Text('Status: ${booking.status}'),
                SizedBox(height: 10),
                Text('Table: ${booking.penaltyStatus}'),
                SizedBox(height: 10),
                Text('Quantity: ${booking.quantity}'),
                SizedBox(height: 10),
                Text('Booking Time: ${booking.createdAt}'),
                // Add more details as needed
              ],
            ),
          ),
        ),
      ),
    );
  }
}

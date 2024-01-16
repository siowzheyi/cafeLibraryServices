import 'package:flutter/material.dart';
import '../Model/order_model.dart';

class OrderDetailPage extends StatelessWidget {
  final OrderModel order;

  const OrderDetailPage({Key? key, required this.order}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Order Details'),
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
                Text('Order No: ${order.orderNo}', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                SizedBox(height: 10),
                Text('Order: ${order.beverageName}'),
                SizedBox(height: 10),
                Text('Status: ${order.status}'),
                SizedBox(height: 10),
                Text('Table: ${order.tableNo}'),
                SizedBox(height: 10),
                Text('Quantity: ${order.quantity}'),
                SizedBox(height: 10),
                Text('Quantity: ${order.totalPrice}'),
                SizedBox(height: 10),
                Text('Order Time: ${order.createdAt}'),
                // Add more details as needed
              ],
            ),
          ),
        ),
      ),
    );
  }
}

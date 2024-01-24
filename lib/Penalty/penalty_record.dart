import 'package:flutter/material.dart';
import '../Model/penalty_model.dart';

class PenaltyDetailPage extends StatelessWidget {
  final PenaltyModel penalty;

  const PenaltyDetailPage({Key? key, required this.penalty}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Penalty Details'),
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
                Text('Penalty for: ${penalty.itemName}', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                SizedBox(height: 10),
                Text('Amount: RM${penalty.penaltyAmount}'),
                SizedBox(height: 10),
                Text('Paid status: ${penalty.penaltyPaidStatus}'),
                SizedBox(height: 10),
                Text('Penalty Time: ${penalty.createdAt}'),
                SizedBox(height: 10),
                if (penalty.penaltyPaidStatus != 'Paid' || penalty.penaltyPaidStatus != 'paid')
                  Container(
                    child: ElevatedButton(
                      onPressed: () {

                      },
                      child: Text('Pay'),
                    ),
                  ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
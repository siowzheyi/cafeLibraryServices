import 'package:cafe_library_services/Beverage/choose_beverage.dart';
import 'package:flutter/material.dart';
import 'beverage_listing.dart';

void main() {
  runApp(
    MaterialApp(
      title: 'Table Reservation',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: TableSelectionPage(),
    ),
  );
}

class TableSelectionPage extends StatefulWidget {
  const TableSelectionPage({super.key});

  @override
  _TableSelectionPageState createState() => _TableSelectionPageState();
}

class _TableSelectionPageState extends State<TableSelectionPage> {
  // Dummy list of available tables
  final List<int> availableTables = List.generate(15, (index) => index + 1);

  // Dummy list of unavailable tables
  final List<int> unavailableTables = [3, 5, 8];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Table Reservation'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: (){
            Navigator.pushReplacement(context, MaterialPageRoute(builder:
                (context) => BeverageListing()));
          },
        ),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            const Text(
              'Available Tables',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 20),
            // Display available tables as buttons
            Wrap(
              spacing: 10,
              runSpacing: 10,
              children: availableTables
                  .map(
                    (tableNumber) => ElevatedButton(
                  onPressed: unavailableTables.contains(tableNumber)
                      ? null // Disable button for unavailable tables
                      : () {
                    // Navigate to the beverage order page with the selected
                    // table
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => BeverageOrderPage(
                          selectedTable: tableNumber,
                        ),
                      ),
                    );
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: unavailableTables.contains(tableNumber)
                        ? Colors.red // Set red background for unavailable
                    // tables
                        : Colors.green,
                    padding: const EdgeInsets.all(16),
                  ),
                  child: Text('$tableNumber'),
                ),
              )
                  .toList(),
            )
          ],
        ),
      ),
    );
  }
}
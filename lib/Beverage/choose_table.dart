import 'package:cafe_library_services/Beverage/add_to_cart.dart';
import 'package:cafe_library_services/Beverage/choose_beverage.dart';
import 'package:flutter/material.dart';
import '../Controller/connection.dart';
import 'beverage_listing.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

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

  List<Table> tables = [];

  Future<void> getTables() async {
    try {
      var response = await http.get(Uri.parse(API.room));
      if (response.statusCode == 200) {
        List<dynamic> decodedData = jsonDecode(response.body);

        setState(() {
          tables = decodedData.map((data) => Table(
            data['tableNo'] ?? '',
          )).toList();
        });

        print(tables);
      }
    } catch (ex) {
      print("Error :: " + ex.toString());
    }
  }

  @override
  void initState() {
    getTables();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Table Reservation'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(
                context, MaterialPageRoute(builder: (context) => BeverageListing()));
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
              children: tables
                  .map(
                    (table) => ElevatedButton(
                  onPressed: () {
                    // Navigate to the beverage order page with the selected table
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => BeverageOrderPage(),
                      ),
                    );
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.green,
                    padding: const EdgeInsets.all(16),
                  ),
                  child: Text('${table.tableNo}'),
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

class Table {
  final String tableNo;

  Table(this.tableNo);
}
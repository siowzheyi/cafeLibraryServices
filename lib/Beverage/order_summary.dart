import 'package:flutter/material.dart';
import 'add_to_cart.dart';
import 'checkout.dart';

class OrderSummaryPage extends StatefulWidget {
  final List<Beverage> selectedBeverages;

  OrderSummaryPage({required this.selectedBeverages});

  @override
  _OrderSummaryPageState createState() => _OrderSummaryPageState();
}

class _OrderSummaryPageState extends State<OrderSummaryPage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Order Summary'),
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
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
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      IconButton(
                        icon: Icon(Icons.remove),
                        onPressed: () {
                          setState(() {
                            if (beverage.quantity > 1) {
                              beverage.quantity--;
                            }
                          });
                        },
                      ),
                      Text('${beverage.quantity}'),
                      IconButton(
                        icon: Icon(Icons.add),
                        onPressed: () {
                          setState(() {
                            beverage.quantity++;
                          });
                        },
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
          SizedBox(height: 16.0),
          ElevatedButton(
            child: Text('Proceed to checkout'),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => CheckoutPage(selectedBeverages: widget.selectedBeverages),
                ),
              );
            },
          )
        ],
      ),
    );
  }
}
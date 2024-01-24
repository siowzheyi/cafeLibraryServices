// import 'package:cafe_library_services/Welcome/home.dart';
// import 'package:flutter/material.dart';
// import 'package:http/http.dart' as http;
// import 'dart:convert';
// import '../Controller/connection.dart';
// import '../Model/receipt_model.dart';
// import '../Welcome/select_library.dart';
//
// void main() {
//   runApp(PaymentPenaltyListing());
// }
//
// class PaymentPenaltyListing extends StatelessWidget {
//   @override
//   Widget build(BuildContext context) {
//     return MaterialApp(
//       theme: ThemeData(
//         primarySwatch: Colors.green,
//       ),
//       home: FutureBuilder<String>(
//         future: getLibraryIdFromSharedPreferences(),
//         builder: (context, snapshot) {
//           if (snapshot.connectionState == ConnectionState.done) {
//             if (snapshot.hasError) {
//               // Handle error
//               return Scaffold(
//                 body: Center(
//                   child: Text('Error: ${snapshot.error}'),
//                 ),
//               );
//             } else {
//               String libraryId = snapshot.data ?? '';
//               return PaymentPenaltyListScreen(cafeId: libraryId);
//             }
//           } else {
//             // While waiting for the Future to complete, show a loading indicator
//             return Scaffold(
//               body: Center(
//                 child: CircularProgressIndicator(),
//               ),
//             );
//           }
//         },
//       ),
//     );
//   }
// }
//
// class PaymentPenaltyListScreen extends StatefulWidget {
//   final String cafeId;
//   final Map<String, String>? headers;
//
//   const PaymentPenaltyListScreen({Key? key, required this.cafeId, this.headers}) : super(key: key);
//
//   @override
//   _PaymentPenaltyListScreenState createState() => _PaymentPenaltyListScreenState();
// }
//
// class FetchPayment {
//   late List<PaymentModel> penalties;
//
//   Future<List<PaymentModel>> getPenaltyList({String? category}) async {
//     try {
//       final String libraryId = await getLibraryIdFromSharedPreferences();
//       final String? token = await getToken();
//       penalties = [];
//
//       var url = Uri.parse('${API.payment}?library_id=$libraryId');
//       var header = {
//         "Content-Type": "application/json",
//         "Authorization": "Bearer $token"
//       };
//
//       var response = await http.get(
//         url,
//         headers: header,
//       );
//
//       if (response.statusCode == 200) {
//         try {
//           Map<String, dynamic> result = jsonDecode(response.body);
//
//           // Check if 'aaData' is a List
//           if (result['data']['aaData'] is List) {
//             List<dynamic> penaltiesList = result['data']['aaData'];
//
//             // Create a List<PaymentModel> by mapping the penalty data
//             List<PaymentModel> penalties = penaltiesList.map((penaltyData) {
//               return PaymentModel.fromJson(penaltyData);
//             }).toList();
//
//             // Now, 'penalties' contains the list of PaymentModel instances
//             return penalties;
//           } else {
//             print('Error: "aaData" is not a List');
//             return [];
//           }
//         } catch (error) {
//           print('Error decoding JSON: $error');
//           return [];
//         }
//       }
//
//       print('Request URL: $url');
//       print('Request Headers: $header');
//       print(response.statusCode);
//       print(response.body);
//       return [];
//     } catch (error) {
//       print('Error fetching penalties: $error');
//       return [];
//     }
//   }
// }
//
// class _PaymentPenaltyListScreenState extends State<PaymentPenaltyListScreen> {
//   late Future<List<PaymentModel>> penaltyList;
//
//   @override
//   void initState() {
//     super.initState();
//     fetchData();
//   }
//
//   Future<void> fetchData() async {
//     penaltyList = FetchPayment().getPenaltyList();
//   }
//
//   @override
//   Widget build(BuildContext context) {
//     return SafeArea(
//       child: Scaffold(
//         appBar: AppBar(
//           title: Text('Payment Listing'),
//           // Add your leading and action icons/buttons here
//         ),
//         body: FutureBuilder<List<PaymentModel>>(
//           future: penaltyList,
//           builder: (context, snapshot) {
//             if (snapshot.connectionState == ConnectionState.waiting) {
//               return Center(child: CircularProgressIndicator());
//             } else if (snapshot.hasError) {
//               return Center(
//                 child: Text('Error: ${snapshot.error}'),
//               );
//             } else {
//               List<PaymentModel> penalties = snapshot.data ?? [];
//
//               if (penalties.isEmpty) {
//                 return Center(child: Text('No payment data available.'));
//               }
//
//               return ListView.builder(
//                 itemCount: penalties.length,
//                 itemBuilder: (context, index) {
//                   var penalty = penalties[index];
//                   return Card(
//                     child: ListTile(
//                       title: Text('Order No: ${penalty.orderNo}'),
//                       subtitle: Text('Item: ${penalty.itemName}\nTotal Price: RM ${penalty.totalPrice}'),
//                       // Add more details or customize the UI as needed
//                     ),
//                   );
//                 },
//               );
//             }
//           },
//         ),
//       ),
//     );
//   }
//
//   @override
//   void dispose() {
//     // Clean up resources, cancel timers, etc.
//     super.dispose();
//   }
// }

class ReceiptModel {
  final int id;
  final String cafeName;
  final String userName;
  final String orderNo;
  final String itemName;
  final double unitPrice;
  final int quantity;
  final String receiptNo;
  final double subtotal;
  final double serviceCharge;
  final double sstAmount;
  final double totalPrice;
  final String description;
  final String createdAt;
  final int status;

  // Required Constructor
  ReceiptModel({
    required this.id,
    required this.cafeName,
    required this.userName,
    required this.orderNo,
    required this.itemName,
    required this.unitPrice,
    required this.quantity,
    required this.receiptNo,
    required this.subtotal,
    required this.serviceCharge,
    required this.sstAmount,
    required this.totalPrice,
    required this.description,
    required this.createdAt,
    required this.status,
  });

  // FromJson method
  factory ReceiptModel.fromJson(Map<String, dynamic> json) {
    return ReceiptModel(
      id: json['id'] ?? '',
      cafeName: json['cafe_name'] ?? '',
      userName: json['library_name'] ?? '',
      orderNo: json['order_no'] ?? '',
      itemName: json['item_name'] ?? '',
      unitPrice: double.parse(json['unit_price'] ?? '0.0'),
      quantity: json['quantity'] ?? '',
      receiptNo: json['receipt_no'] ?? '',
      subtotal: double.parse(json['subtotal'] ?? '0.0'),
      serviceCharge: double.parse(json['service_charge_amount'] ?? '0.0'),
      sstAmount: double.parse(json['sst_amount'] ?? '0.0'),
      totalPrice: double.parse(json['total_price'] ?? '0.0'),
      description: json['description'] ?? '',
      createdAt: json['created_at'] ?? '',
      status: json['status'] ?? '',
    );
  }

  // ToJson method
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'cafe_name': cafeName,
      'library_name': userName,
      'order_no': orderNo,
      'item_name': itemName,
      'unit_price': unitPrice,
      'quantity': quantity,
      'receipt_no': receiptNo,
      'subtotal': subtotal,
      'service_charge_amount': serviceCharge,
      'sst_amount': sstAmount,
      'total_price': totalPrice,
      'description': description,
      'created_at': createdAt,
      'status': status,
    };
  }
}

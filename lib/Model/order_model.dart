class OrderModel {
  String beverageName;
  int quantity;
  double unitPrice;
  double totalPrice;
  String orderNo;
  String tableNo;
  String paymentStatus;

  // constructor
  OrderModel({
    required this.beverageName,
    required this.quantity,
    required this.unitPrice,
    required this.totalPrice,
    required this.orderNo,
    required this.tableNo,
    required this.paymentStatus
  });

  // factory method to create an announcement from a map
  factory OrderModel.fromJson(Map<String, dynamic> json) {
    return OrderModel(
        beverageName: json['beverage_name'] ?? '',
        quantity: json['quantity'] ?? '',
        unitPrice: json['unit_price'] ?? '',
        totalPrice: json['total_price'] ?? '',
        orderNo: json['order_no'] ?? '',
        tableNo: json['table_no'] ?? '',
        paymentStatus: json['payment_status'] ?? '',
    );
  }

  // convert the announcement instance to a map
  Map<String, dynamic> toJson() {
    return {
      'beverage_name': beverageName,
      'quantity': quantity,
      'unit_price': unitPrice,
      'total_price': totalPrice,
      'order_no': orderNo,
      'table_no': tableNo,
      'payment_status': paymentStatus,
    };
  }
}
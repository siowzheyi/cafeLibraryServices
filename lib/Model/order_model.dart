class OrderModel {
  final int id;
  final String status;
  final String orderNo;
  final String tableNo;
  final String cafeName;
  final String beverageName;
  final int quantity;
  final double totalPrice;
  final String createdAt;

  OrderModel({
    required this.id,
    required this.status,
    required this.orderNo,
    required this.tableNo,
    required this.cafeName,
    required this.beverageName,
    required this.quantity,
    required this.totalPrice,
    required this.createdAt,
  });

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    return OrderModel(
      id: json['id'],
      status: json['status'],
      orderNo: json['order_no'],
      tableNo: json['table_no'],
      cafeName: json['cafe_name'],
      beverageName: json['beverage_name'],
      quantity: json['quantity'],
      totalPrice: json['total_price'],
      createdAt: json['created_at'],
    );
  }

  // convert the announcement instance to a map
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'status': status,
      'order_no': orderNo,
      'table_no': tableNo,
      'cafe_name': cafeName,
      'beverage_name': beverageName,
      'quantity': quantity,
      'total_price': totalPrice,
      'created_at': createdAt,
    };
  }
}
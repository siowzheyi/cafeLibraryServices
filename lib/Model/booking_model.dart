class BookingModel {
  final int id;
  final String status;
  final int itemId;
  final String itemName;
  final String libraryName;
  final int libraryId;
  final int penaltyStatus;
  final int quantity;
  final String createdAt;

  // constructor
  BookingModel({
    required this.id,
    required this.status,
    required this.itemId,
    required this.itemName,
    required this.libraryName,
    required this.libraryId,
    required this.penaltyStatus,
    required this.quantity,
    required this.createdAt,
  });

  // factory method to create an announcement from a map
  factory BookingModel.fromJson(Map<String, dynamic> json) {
    return BookingModel(
      id: json['id'] ?? '',
      status: json['status'] ?? '',
      itemId: json['item_id'] ?? '',
      itemName: json['item_name'] ?? '',
      libraryName: json['library_name'] ?? '',
      libraryId: json['library_id'] ?? '',
      penaltyStatus: json['penalty_status'] ?? '',
      quantity: json['quantity'] ?? '',
      createdAt: json['created_at'] ?? '',
    );
  }

  // convert the announcement instance to a map
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'status': status,
      'item_id': itemId,
      'item_name': itemName,
      'library_name': libraryName,
      'library_id': libraryId,
      'penalty_status': penaltyStatus,
      'quantity': quantity,
      'created_at': createdAt,
    };
  }
}
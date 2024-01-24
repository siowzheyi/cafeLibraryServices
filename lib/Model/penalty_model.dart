class PenaltyModel {
  final int id;
  final String userName;
  final int itemId;
  final String itemName;
  final String itemPicture;
  final String libraryName;
  final int libraryId;
  final int penaltyAmount;
  final String penaltyPaidStatus;
  final int quantity;
  final String createdAt;

  // constructor
  PenaltyModel({
    required this.id,
    required this.userName,
    required this.itemId,
    required this.itemName,
    required this.itemPicture,
    required this.libraryName,
    required this.libraryId,
    required this.penaltyAmount,
    required this.penaltyPaidStatus,
    required this.quantity,
    required this.createdAt,
  });

  // factory method to create an announcement from a map
  factory PenaltyModel.fromJson(Map<String, dynamic> json) {
    return PenaltyModel(
      id: json['id'] ?? '',
      userName: json['user_name'] ?? '',
      itemId: json['item_id'] ?? '',
      itemName: json['item_name'] ?? '',
      itemPicture: json['item_picture'] ?? '',
      libraryName: json['library_name'] ?? '',
      libraryId: json['library_id'] ?? '',
      penaltyAmount: json['penalty_amount'] ?? '',
      penaltyPaidStatus: json['penalty_paid_status'] ?? '',
      quantity: json['quantity'] ?? '',
      createdAt: json['created_at'] ?? '',
    );
  }

  // convert the announcement instance to a map
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_name': userName,
      'item_id': itemId,
      'item_name': itemName,
      'item_picture': itemPicture,
      'library_name': libraryName,
      'library_id': libraryId,
      'penalty_amount': penaltyAmount,
      'penalty_paid_status': penaltyPaidStatus,
      'quantity': quantity,
      'created_at': createdAt,
      // Add other properties as needed
    };
  }
}
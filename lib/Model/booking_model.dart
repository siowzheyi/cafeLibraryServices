class BookingModel {
  final int bookingId;
  final String status;
  final int itemId;
  final String itemName;
  final String libraryName;
  final int libraryId;
  late final int penaltyStatus;
  final int quantity;
  final String createdAt;
  final String endBooked;
  final String end;

  // constructor
  BookingModel({
    required this.bookingId,
    required this.status,
    required this.itemId,
    required this.itemName,
    required this.libraryName,
    required this.libraryId,
    required this.penaltyStatus,
    required this.quantity,
    required this.createdAt,
    required this.endBooked,
    required this.end,
  });

  // factory method
  factory BookingModel.fromJson(Map<String, dynamic> json) {
    return BookingModel(
      bookingId: json['id'] ?? '',
      status: json['status'] ?? '',
      itemId: json['item_id'] ?? '',
      itemName: json['item_name'] ?? '',
      libraryName: json['library_name'] ?? '',
      libraryId: json['library_id'] ?? '',
      penaltyStatus: json['penalty_status'] ?? '',
      quantity: json['quantity'] ?? '',
      createdAt: json['created_at'] ?? '',
      endBooked: json['end_booked_at'] ?? '',
      end: json['end_at'] ?? '',
    );
  }

  // convert
  Map<String, dynamic> toJson() {
    return {
      'id': bookingId,
      'status': status,
      'item_id': itemId,
      'item_name': itemName,
      'library_name': libraryName,
      'library_id': libraryId,
      'penalty_status': penaltyStatus,
      'quantity': quantity,
      'created_at': createdAt,
      'end_booked_at': endBooked,
      'end_at': end,
    };
  }
}
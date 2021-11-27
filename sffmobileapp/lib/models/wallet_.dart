class Wallet {
  final int id;
  final String title;
  final int userId;
  final DateTime dateBegin;
  final DateTime dateEnd;
  final DateTime createdAt;

  Wallet({
    required this.id,
    required this.title,
    required this.userId,
    required this.dateBegin,
    required this.dateEnd,
    required this.createdAt,
  });

  factory Wallet.formJson(Map<String, dynamic> json) {
    return Wallet(
      id: int.parse(json["id"]),
      title: json["title"],
      userId: int.parse(json['user_id']),
      dateBegin: DateTime.parse(json["date_begin"]),
      dateEnd: DateTime.parse(json["date_end"]),
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}

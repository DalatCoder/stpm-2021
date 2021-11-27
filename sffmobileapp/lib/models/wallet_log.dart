class WalletLog {
  final int id;
  final int walletId;
  final int categoryId;
  final String title;
  final String type;
  final int amount;
  final DateTime logDate;
  final DateTime createdAt;

  WalletLog({
    required this.id,
    required this.walletId,
    required this.categoryId,
    required this.title,
    required this.type,
    required this.amount,
    required this.logDate,
    required this.createdAt,
  });

  factory WalletLog.fromJson(Map<String, dynamic> json) {
    return WalletLog(
      id: int.parse(json["id"]),
      walletId: int.parse(json["wallet_id"]),
      categoryId: int.parse(json["category_id"]),
      title: json["title"],
      type: json["type"],
      amount: int.parse(json["amount"]),
      logDate: DateTime.parse(json["log_date"]),
      createdAt: DateTime.parse(json["created_at"]),
    );
  }
}

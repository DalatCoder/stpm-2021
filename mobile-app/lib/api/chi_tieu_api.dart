import 'dart:convert';

import 'package:date_time_picker/date_time_picker.dart';
import 'package:http/http.dart' as http;
import 'package:quan_ly_chi_tieu_ca_nhan/models/chi_tiet_chi_tieu.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/chi_tieu.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/nguoi_dung.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/utils/constants.dart';

class ChiTieuAPI {
  final String _urlChiTieu = "$kURL/api/v1/wallet-logs/aggregate-by-date";
  final String _urlChiTietChiTieu = '$kURL/api/v1/wallet-logs/get-logs-by-date';
  final String _urlNewOutcomeLog = "$kURL/api/v1/wallet-logs";

  dynamic myEncode(dynamic item) {
    if (item is DateTime) {
      return item.toIso8601String();
    }
    return item;
  }

  Future<List<ChiTieu>> layDanhSachChiTieu(
      NguoiDung nguoiDung, int quanLyTienId) async {
    final http.Response response = await http.get(
      '$_urlChiTieu?wallet_id=$quanLyTienId',
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
        'Cookie': 'PHPSESSID=' + nguoiDung.sid
      },
    );

    if (response.statusCode != 200) return [];

    var jsonBody = jsonDecode(response.body);
    var list = jsonBody["data"];

    List<ChiTieu> dsChiTieu = [];

    for (var json in list) {
      ChiTieu chiTieu = ChiTieu.fromJson(json);
      dsChiTieu.add(chiTieu);
    }

    return dsChiTieu;
  }

  Future<List<dynamic>> layDanhSachChiTietChiTieu(
      NguoiDung nguoiDung, DateTime ngay, int walletId) async {
    try {
      String formattedDate = DateFormat('yyyy-MM-dd').format(ngay);

      final http.Response response = await http.get(
        '$_urlChiTietChiTieu?date=$formattedDate&wallet_id=$walletId',
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
          'Cookie': 'PHPSESSID=' + nguoiDung.sid
        },
      );

      if (response.statusCode != 200) return [];

      var jsonBody = jsonDecode(response.body);
      List<dynamic> list = jsonBody["data"];

      return list;
    } on Exception catch (_) {
      return [];
    }

    // List<ChiTietChiTieu> dsChiTietChiTieu = [];
    // for (var json in list) {
    //   ChiTietChiTieu chiTietChiTieu = ChiTietChiTieu.fromJson(json);
    //   dsChiTietChiTieu.add(chiTietChiTieu);
    // }

    // return dsChiTietChiTieu;
  }

  Future<bool> themChiTieu(
      {String nhom,
      String tenChiTieu,
      int soTien,
      DateTime ngayChiTieu,
      int idNguoiDung,
      var walletId}) async {
    var body = {
      "category_id": nhom,
      "title": tenChiTieu,
      "amount": soTien,
      "log_date": DateFormat('yyyy-MM-dd').format(ngayChiTieu),
      "wallet_id": walletId,
      "type": "out"
    };

    var bodyJson = jsonEncode(body);
    http.Response response = await http.post(
      _urlNewOutcomeLog,
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
      body: bodyJson,
    );

    // print(bodyJson);
    // print(response.statusCode);

    if (response.statusCode == 201) {
      return true;
    }
    return false;
  }
}

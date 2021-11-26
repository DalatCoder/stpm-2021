import 'dart:convert';

import 'package:date_time_picker/date_time_picker.dart';
import 'package:http/http.dart' as http;
import 'package:quan_ly_chi_tieu_ca_nhan/models/list_quan_ly_tien.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/nguoi_dung.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/quan_ly_tien_thong_ke_chi_tiet.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/quan_ly_tien_thong_ke_khoan_chi.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/quan_ly_tien_thong_ke_nguon_thu.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/quan_ly_tien_thong_ke_tong_quan.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/utils/constants.dart';

class QuanLyTienApi {
  final String _url = "$kURL/api/v1/wallets/by-user";
  final String _urlGetAllIncomeCategories = "$kURL/api/v1/categories/incomes";
  final String _urlGetAllOutcomeCategories = "$kURL/api/v1/categories/outcomes";
  final String _urlGetAllWalletsByUserId = "$kURL/api/v1/wallets/by-user";
  final String _urlThemQuanLyTien = "$kURL/api/v1/wallets";
  final String _urlThongKeTongQuan = '$kURL/api/v1/wallets/statistics';
  final String _urlThongKeChiTiet = '$kURL/api/quanlytien/thongkechitiet';
  final String _urlThongKeNguonThuTongQuan = '$kURL/api/v1/wallets/logs';
  final String _urlThongKeKhoanchiTongQuan =
      '$kURL/api/quanlytien/thongkekhoanchi';
  final String _urlThemNguonThu = "$kURL/api/v1/wallet-logs";

  dynamic myEncode(dynamic item) {
    if (item is DateTime) {
      return item.toString();
    }
    return item;
  }

  Future<List<ListQuanLyTien>> getAllQuanLyTien(NguoiDung nguoiDung) async {
    var id = nguoiDung.id;
    var sid = nguoiDung.sid;

    http.Response response = await http.get(
      '$_url?id=$id',
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
        'Cookie': 'SID=' + sid
      },
    );

    if (response.statusCode != 200) return null;

    var responseObject = jsonDecode(response.body);

    List<dynamic> list = responseObject["data"] ?? [];

    List<ListQuanLyTien> dsQuanLyTien = [];

    for (var json in list) {
      ListQuanLyTien quanLyTien = ListQuanLyTien.fromJson(json);
      dsQuanLyTien.add(quanLyTien);
    }

    return dsQuanLyTien;
  }

  Future<Map<String, dynamic>> getQuanLyTienThongKeTongQuan(
      NguoiDung nguoiDung) async {
    http.Response response = await http.get(
      '$_urlThongKeTongQuan',
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
        'Cookie': 'PHPSESSID=' + nguoiDung.sid
      },
    );

    if (response.statusCode != 200) return null;

    var jsonBody = jsonDecode(response.body);

    return jsonBody["data"];
  }

  Future<QuanLyTienThongKeChiTiet> getQuanLyTienThongKeChiTiet(
      int quanLyTienID) async {
    http.Response response = await http.get(
      '$_urlThongKeChiTiet?quanlytien_id=$quanLyTienID',
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );

    if (response.statusCode != 200) return null;

    return QuanLyTienThongKeChiTiet.fromJson(jsonDecode(response.body));
  }

  Future<List<dynamic>> getDanhSachNguonThu(int quanLyTienID) async {
    http.Response response = await http.get(
      '$_urlThongKeNguonThuTongQuan?id=$quanLyTienID',
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );

    if (response.statusCode != 200) return [];

    var responseBody = jsonDecode(response.body);
    List<dynamic> list = responseBody["data"];

    return list;
  }

  Future<List<QuanLyTienThongKeKhoanChi>> getQuanLyTienThongKeKhoanChiTongQuan(
      int quanLyTienID) async {
    http.Response response = await http.get(
      '$_urlThongKeKhoanchiTongQuan?quanlytien_id=$quanLyTienID',
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );

    if (response.statusCode != 200) return null;

    List<dynamic> list = jsonDecode(response.body);

    List<QuanLyTienThongKeKhoanChi> dsKhoanChi = [];
    for (var json in list) {
      QuanLyTienThongKeKhoanChi khoanChi =
          QuanLyTienThongKeKhoanChi.fromJson(json);
      dsKhoanChi.add(khoanChi);
    }

    return dsKhoanChi;
  }

  Future<List<dynamic>> getAllWalletsByUserId(int user_id) async {
    http.Response response = await http.get(
      "$_urlGetAllWalletsByUserId?id=$user_id",
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );

    if (response.statusCode != 200) return [];

    var jsonBody = jsonDecode(response.body);
    List<dynamic> list = jsonBody["data"];

    return list;
  }

  Future<List<dynamic>> getAllIncomesCategory() async {
    http.Response response = await http.get(
      '$_urlGetAllIncomeCategories',
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );

    if (response.statusCode != 200) return null;

    var jsonBody = jsonDecode(response.body);
    List<dynamic> list = jsonBody["data"];

    return list;
  }

  Future<List<dynamic>> getAllOutcomesCategory() async {
    http.Response response = await http.get(
      '$_urlGetAllOutcomeCategories',
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );

    if (response.statusCode != 200) return null;

    var jsonBody = jsonDecode(response.body);
    List<dynamic> list = jsonBody["data"];

    return list;
  }

  Future<bool> themQuanLyTien(
      {NguoiDung nguoiDung, DateTime ngayBD, DateTime ngayKT}) async {
    var body = {
      "user_id": nguoiDung.id,
      "date_begin": DateFormat('yyyy-MM-dd').format(ngayBD),
      "date_end": DateFormat('yyyy-MM-dd').format(ngayKT),
      "format": "Y-m-d"
    };

    var bodyJson = jsonEncode(body);

    http.Response response = await http.post(
      _urlThemQuanLyTien,
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
        'Cookie': 'SID=' + nguoiDung.sid
      },
      body: bodyJson,
    );

    if (response.statusCode == 201) {
      return true;
    }
    return false;
  }

  Future<bool> themNguonThu({int idQuanLyTien, int soTien, String nhom}) async {
    DateTime now = DateTime.now();
    String formattedDate = DateFormat('yyyy-MM-dd').format(now);

    var body = {
      "wallet_id": idQuanLyTien,
      "amount": soTien,
      "category_id": nhom,
      "type": "in",
      "log_date": formattedDate
    };

    var bodyJson = jsonEncode(body);

    http.Response response = await http.post(
      _urlThemNguonThu,
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
      body: bodyJson,
    );

    if (response.statusCode == 201) {
      return true;
    }
    return false;
  }
}

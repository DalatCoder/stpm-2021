import 'package:date_time_picker/date_time_picker.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/api/quan_ly_tien_api.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/components/nut_bam.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/components/rounded_summary_box.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/components/rounded_summary_card.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/components/transaction_iten.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/nguoi_dung.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/quan_ly_tien_thong_ke_chi_tiet.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/quan_ly_tien_thong_ke_khoan_chi.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/models/quan_ly_tien_thong_ke_nguon_thu.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/screens/chi_tieu/lich_su_chi_tieu_page.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/screens/quan_ly_tien/them_khoan_thu_page.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/utils/color_picker.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/utils/constants.dart';

class QuanLyTienChiTietPage extends StatefulWidget {
  final int quanLyTienID;
  final NguoiDung nguoiDung;
  final Function onChanged;

  QuanLyTienChiTietPage({
    @required this.quanLyTienID,
    @required this.nguoiDung,
    this.onChanged,
  });

  @override
  _QuanLyTienChiTietPageState createState() => _QuanLyTienChiTietPageState();
}

class _QuanLyTienChiTietPageState extends State<QuanLyTienChiTietPage> {
  final dateFormat = new DateFormat('dd-MM-yyyy');
  final currencyFormat = new NumberFormat('###,###,###,###');
  ColorPicker colorPicker = ColorPicker();
  QuanLyTienApi _quanLyTienApi = QuanLyTienApi();

  QuanLyTienThongKeChiTiet thongKe = QuanLyTienThongKeChiTiet();
  List<dynamic> dsNguonThu = [];
  List<QuanLyTienThongKeKhoanChi> dsKhoanChi = [];

  void getThongKe() async {
    QuanLyTienThongKeChiTiet data =
        await _quanLyTienApi.getQuanLyTienThongKeChiTiet(widget.quanLyTienID);

    if (data != null) {
      setState(() {
        thongKe = data;
      });
    }
  }

  void getDanhSachNguonThu() async {
    var data = await _quanLyTienApi.getDanhSachNguonThu(widget.quanLyTienID);

    if (data != null) {
      setState(() {
        dsNguonThu = data;
      });
    }
  }

  void getDanhSachKhoanChi() async {
    List<QuanLyTienThongKeKhoanChi> data = await _quanLyTienApi
        .getQuanLyTienThongKeKhoanChiTongQuan(widget.quanLyTienID);

    if (data != null) {
      setState(() {
        dsKhoanChi = data;
      });
    }
  }

  List<TransactionItem> renderDanhSachNguonThu() {
    List<TransactionItem> widgets = [];

    for (var nguonThu in dsNguonThu) {
      var amount = int.tryParse(nguonThu["amount"]);

      TransactionItem item = TransactionItem(
        barColor: colorPicker.random(),
        icon: FontAwesomeIcons.home,
        iconColor: Colors.purple,
        amount: '+ ${currencyFormat.format(amount)}',
        title: '${nguonThu["category"]["name"]}',
        textColor: Colors.green,
      );

      widgets.add(item);
    }

    return widgets;
  }

  List<TransactionItem> renderDanhSachKhoanChi() {
    List<TransactionItem> widgets = [];

    for (var khoanChi in dsKhoanChi) {
      TransactionItem item = TransactionItem(
        barColor: colorPicker.random(),
        icon: khoanChi.icon,
        iconColor: khoanChi.iconColor,
        amount: '- ${currencyFormat.format(khoanChi.soTien)}',
        title: '${khoanChi.nhom}',
        textColor: Colors.red,
      );

      widgets.add(item);
    }

    return widgets;
  }

  @override
  void initState() {
    // getThongKe();
    getDanhSachNguonThu();
    // getDanhSachKhoanChi();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Chi ti???t k??? ho???ch qu???n l?? ti???n'),
      ),
      body: SafeArea(
        child: Container(
          color: Colors.white,
          padding: kPaddingMainPage,
          child: ListView(
            children: [
              RoundedSummaryCard(
                title: 'S??? ti???n ??ang qu???n l??',
                money: '${currencyFormat.format(thongKe.soTienHienCo)} ???',
                icon: Icons.account_balance,
                iconBgColor: Colors.blue[200],
                iconColor: Colors.orange,
                titleColor: Colors.purpleAccent,
                onPressed: () {
                  Navigator.push(context, MaterialPageRoute(
                    builder: (context) {
                      return ThemKhoanThuPage(
                        idQuanLyTien: widget.quanLyTienID,
                        onSuccess: () {
                          // getThongKe();
                          getDanhSachNguonThu();
                          // getDanhSachKhoanChi();

                          if (widget.onChanged != null) widget.onChanged();
                        },
                      );
                    },
                  ));
                },
              ),
              SizedBox(height: 25.0),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  RoundedSummaryBox(
                    title: 'S??? d??',
                    money: '${currencyFormat.format(thongKe.soDu)} ???',
                    icon: Icons.euro,
                    iconColor: Colors.green.shade800,
                    iconBgColor: Colors.green.shade200,
                  ),
                  SizedBox(width: 20.0),
                  RoundedSummaryBox(
                    title: 'T???ng chi',
                    money: '${currencyFormat.format(thongKe.soTienDaSuDung)} ???',
                    icon: Icons.money_off,
                    iconColor: Colors.red.shade800,
                    iconBgColor: Colors.red.shade200,
                  ),
                ],
              ),
              SizedBox(height: 15.0),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  RoundedSummaryBox(
                    title: 'T??? ng??y',
                    money: '${dateFormat.format(thongKe.ngayBD)}',
                    icon: Icons.date_range,
                    iconColor: Colors.blue.shade800,
                    iconBgColor: Colors.blue.shade200,
                  ),
                  SizedBox(width: 20.0),
                  RoundedSummaryBox(
                    title: 'C??n l???i',
                    money: '${thongKe.soNgayConLai} ng??y',
                    icon: Icons.calendar_today,
                    iconColor: Colors.yellow.shade800,
                    iconBgColor: Colors.yellow.shade200,
                  ),
                ],
              ),
              SizedBox(height: 15.0),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  RoundedSummaryBox(
                    title: 'H???n m???c chi ti??u',
                    money: '${currencyFormat.format(thongKe.hanMucChiTieu)} ???',
                    icon: Icons.warning,
                    iconColor: Colors.orange.shade800,
                    iconBgColor: Colors.orange.shade200,
                  ),
                  SizedBox(width: 20.0),
                  RoundedSummaryBox(
                    title: 'S??? ng??y v?????t m???c',
                    money: '${thongKe.soNgayVuotMuc}',
                    icon: Icons.warning_sharp,
                    iconColor: Colors.purple.shade800,
                    iconBgColor: Colors.purple.shade200,
                  ),
                ],
              ),
              SizedBox(height: 30.0),
              Padding(
                padding: const EdgeInsets.only(left: 5.0),
                child: Text('Chi ti???t c??c ngu???n thu:', style: kTitleTextStyle),
              ),
              SizedBox(height: 10.0),
              Column(
                children: renderDanhSachNguonThu(),
              ),
              SizedBox(height: 30.0),
              Padding(
                padding: const EdgeInsets.only(left: 5.0),
                child: Text('Th???ng k?? c??c kho???n chi:', style: kTitleTextStyle),
              ),
              SizedBox(height: 10.0),
              Column(
                children: renderDanhSachKhoanChi(),
              ),
              SizedBox(height: 30.0),
              NutBam(
                textName: 'Xem l???ch s??? chi ti??u',
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) {
                        return LichSuChiTieuPage(
                          quanLyTienID: widget.quanLyTienID,
                          nguoiDung: widget.nguoiDung,
                          onChanged: () {
                            // getThongKe();
                            getDanhSachNguonThu();
                            // getDanhSachKhoanChi();

                            if (widget.onChanged != null) widget.onChanged();
                          },
                        );
                      },
                    ),
                  );
                },
              )
            ],
          ),
        ),
      ),
    );
  }
}

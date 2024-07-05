import 'dart:convert';
import 'package:http/http.dart' as http;

Future<Map<String, dynamic>> postDataToServer(String action, Map<String, dynamic> data) async {
  var url = 'http://localhost:8080/customer_table/manage_customer.php'; // Adjust your URL as necessary
  var body = json.encode(data); // Encode data as JSON

  try {
    var response = await http.post(Uri.parse(url),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: body);

    if (response.statusCode == 200) {
      return json.decode(response.body); // Decode JSON response
    } else {
      throw Exception('Failed to load data');
    }
  } catch (e) {
    print('Error: $e');
    throw Exception('Failed to connect to server');
  }
}

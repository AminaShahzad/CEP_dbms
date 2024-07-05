import 'package:flutter/material.dart';
import 'api_file.dart'; // Assuming this file contains API functions

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Customer Management App',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: CustomerScreen(),
    );
  }
}

class CustomerScreen extends StatefulWidget {
  @override
  _CustomerScreenState createState() => _CustomerScreenState();
}

class _CustomerScreenState extends State<CustomerScreen> {
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _phonenoController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();

  List<Map<String, dynamic>> _customers = [];
  List<Map<String, dynamic>> _filteredCustomers = [];

  @override
  void initState() {
    super.initState();
    _fetchCustomers();
  }

  Future<void> _fetchCustomers() async {
    try {
      var response = await postDataToServer("fetch_data", {});
      if (response["success"] == true) {
        setState(() {
          _customers = List<Map<String, dynamic>>.from(response["data"]);
          _filteredCustomers = List.from(_customers);
        });
      } else {
        print('Failed to load customers: ${response["message"]}');
      }
    } catch (e) {
      print('Error fetching customers: $e');
    }
  }

  void _filterCustomers(String query) {
    setState(() {
      if (query.isEmpty) {
        _filteredCustomers = List.from(_customers);
      } else {
        _filteredCustomers = _customers
            .where((customer) =>
                customer['CUST_NAME']
                    .toLowerCase()
                    .contains(query.toLowerCase()) ||
                customer['PHONE_NUM']
                    .toLowerCase()
                    .contains(query.toLowerCase()))
            .toList();
      }
    });
  }

  void _showUpdateDialog(Map<String, dynamic> customer) {
    _nameController.text = customer['CUST_NAME'] ?? '';
    _emailController.text = customer['EMAIL'] ?? '';
    _phonenoController.text = customer['PHONE_NUM'] ?? '';
    _addressController.text = customer['ADDRESS'] ?? '';

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Update Customer'),
          content: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                TextField(
                  controller: _nameController,
                  decoration: InputDecoration(labelText: 'Name'),
                ),
                SizedBox(height: 10),
                TextField(
                  controller: _emailController,
                  decoration: InputDecoration(labelText: 'Email'),
                ),
                SizedBox(height: 10),
                TextField(
                  controller: _phonenoController,
                  decoration: InputDecoration(labelText: 'Phone No'),
                ),
                SizedBox(height: 10),
                TextField(
                  controller: _addressController,
                  decoration: InputDecoration(labelText: 'Address'),
                ),
                SizedBox(height: 10),
              ],
            ),
          ),
          actions: <Widget>[
            TextButton(
              child: Text('Cancel'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            ElevatedButton(
              child: Text('Update'),
              onPressed: () async {
                await _updateCustomer(customer);
                Navigator.of(context).pop();
              },
            ),
            ElevatedButton(
              child: Text('Delete'),
              onPressed: () async {
                await _deleteCustomer(customer);
                Navigator.of(context).pop();
              },
            ),
          ],
        );
      },
    );
  }

  Future<void> _updateCustomer(Map<String, dynamic> customer) async {
    try {
      var data = {
        "action": "update_customer",
        "cust_id": customer['CUST_ID'],
        "name": _nameController.text,
        "email": _emailController.text,
        "phoneno": _phonenoController.text,
        "address": _addressController.text,
      };

      var response = await postDataToServer("update_customer", data);
      if (response["success"] == true) {
        _fetchCustomers();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Customer updated successfully"),
            duration: Duration(seconds: 2),
          ),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Failed to update customer. Please try again."),
            duration: Duration(seconds: 2),
          ),
        );
      }
    } catch (e) {
      print('Error updating customer: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text("An unexpected error occurred. Please try again."),
          duration: Duration(seconds: 2),
        ),
      );
    }
  }

  Future<void> _deleteCustomer(Map<String, dynamic> customer) async {
    try {
      var data = {
        "action": "delete",
        "custid": customer['CUST_ID'],
        "phoneno": customer['PHONE_NUM'],
      };

      var response = await postDataToServer("delete", data);
      if (response["success"] == true) {
        _fetchCustomers();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Customer deleted successfully"),
            duration: Duration(seconds: 2),
          ),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Failed to delete customer. Please try again."),
            duration: Duration(seconds: 2),
          ),
        );
      }
    } catch (e) {
      print('Error deleting customer: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text("An unexpected error occurred. Please try again."),
          duration: Duration(seconds: 2),
        ),
      );
    }
  }

  Future<void> _addCustomer() async {
    try {
      var data = {
        "action": "add_customer",
        "name": _nameController.text,
        "email": _emailController.text,
        "phoneno": _phonenoController.text,
        "address": _addressController.text,
      };

      var response = await postDataToServer("add_customer", data);
      if (response["success"] == true) {
        _fetchCustomers();
        _showSuccessDialog();
        _clearInputFields();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Failed to add customer. Please try again."),
            duration: Duration(seconds: 2),
          ),
        );
      }
    } catch (e) {
      print('Error adding customer: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text("An unexpected error occurred. Please try again."),
          duration: Duration(seconds: 2),
        ),
      );
    }
  }

  void _showSuccessDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Success'),
          content: Text('Customer saved successfully'),
          actions: <Widget>[
            TextButton(
              child: Text('OK'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
          ],
        );
      },
    );
  }

  void _clearInputFields() {
    _nameController.clear();
    _emailController.clear();
    _phonenoController.clear();
    _addressController.clear();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Customer Management'),
      ),
      body: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          // Left Side - Customer List and Actions
          Expanded(
            flex: 2,
            child: Column(
              children: <Widget>[
                Padding(
                  padding: EdgeInsets.all(16.0),
                  child: TextField(
                    onChanged: _filterCustomers,
                    decoration: InputDecoration(
                      labelText: 'Search',
                      prefixIcon: Icon(Icons.search),
                    ),
                  ),
                ),
                Expanded(
                  child: ListView.builder(
                    itemCount: _filteredCustomers.length,
                    itemBuilder: (context, index) {
                      var customer = _filteredCustomers[index];
                      return ListTile(
                        title: Text(customer['CUST_NAME']),
                        subtitle: Text(customer['PHONE_NUM']),
                        onTap: () {
                          _showUpdateDialog(customer);
                        },
                      );
                    },
                  ),
                ),
              ],
            ),
          ),

          // Right Side - Add Customer Form
          Expanded(
            flex: 3,
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: <Widget>[
                  Text(
                    'Add New Customer',
                    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                  ),
                  SizedBox(height: 20),
                  TextField(
                    controller: _nameController,
                    decoration: InputDecoration(labelText: 'Name'),
                  ),
                  SizedBox(height: 10),
                  TextField(
                    controller: _emailController,
                    decoration: InputDecoration(labelText: 'Email'),
                  ),
                  SizedBox(height: 10),
                  TextField(
                    controller: _phonenoController,
                    decoration: InputDecoration(labelText: 'Phone No'),
                  ),
                  SizedBox(height: 10),
                  TextField(
                    controller: _addressController,
                    decoration: InputDecoration(labelText: 'Address'),
                  ),
                  SizedBox(height: 20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: <Widget>[
                      ElevatedButton(
                        onPressed: _addCustomer,
                        child: Text('Save Customer'),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

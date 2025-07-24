# HRMS API Integration Guide for Flutter

This guide provides comprehensive information for integrating the HRMS Laravel backend with your Flutter application.

## Base Configuration

### API Base URL
```
http://localhost:8000/api
```

### Authentication
The API uses Bearer token authentication. Include the token in the Authorization header:
```
Authorization: Bearer {your_token}
```

## Flutter Integration Examples

### 1. Authentication

#### Login
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class AuthService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  static Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: json.encode({
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Login failed');
    }
  }

  static Future<Map<String, dynamic>> getProfile(String token) async {
    final response = await http.get(
      Uri.parse('$baseUrl/profile'),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to get profile');
    }
  }
}
```

### 2. Employee Management

#### Get All Employees
```dart
class EmployeeService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  static Future<List<Employee>> getEmployees(String token) async {
    final response = await http.get(
      Uri.parse('$baseUrl/employees'),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return (data['data']['data'] as List)
          .map((json) => Employee.fromJson(json))
          .toList();
    } else {
      throw Exception('Failed to load employees');
    }
  }

  static Future<Employee> createEmployee(String token, Map<String, dynamic> employeeData) async {
    final response = await http.post(
      Uri.parse('$baseUrl/employees'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: json.encode(employeeData),
    );

    if (response.statusCode == 201) {
      final data = json.decode(response.body);
      return Employee.fromJson(data['data']);
    } else {
      throw Exception('Failed to create employee');
    }
  }
}
```

### 3. Offer Letter Management

#### Get Offer Letters
```dart
class OfferLetterService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  static Future<List<OfferLetter>> getOfferLetters(String token) async {
    final response = await http.get(
      Uri.parse('$baseUrl/offer-letters'),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return (data['data']['data'] as List)
          .map((json) => OfferLetter.fromJson(json))
          .toList();
    } else {
      throw Exception('Failed to load offer letters');
    }
  }

  static Future<OfferLetter> createOfferLetter(String token, Map<String, dynamic> offerData) async {
    final response = await http.post(
      Uri.parse('$baseUrl/offer-letters'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: json.encode(offerData),
    );

    if (response.statusCode == 201) {
      final data = json.decode(response.body);
      return OfferLetter.fromJson(data['data']);
    } else {
      throw Exception('Failed to create offer letter');
    }
  }

  static Future<void> sendOfferLetter(String token, int offerId) async {
    final response = await http.patch(
      Uri.parse('$baseUrl/offer-letters/$offerId/send'),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode != 200) {
      throw Exception('Failed to send offer letter');
    }
  }
}
```

### 4. Payroll Management

#### Get Payrolls
```dart
class PayrollService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  static Future<List<Payroll>> getPayrolls(String token) async {
    final response = await http.get(
      Uri.parse('$baseUrl/payrolls'),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return (data['data']['data'] as List)
          .map((json) => Payroll.fromJson(json))
          .toList();
    } else {
      throw Exception('Failed to load payrolls');
    }
  }

  static Future<Payroll> createPayroll(String token, Map<String, dynamic> payrollData) async {
    final response = await http.post(
      Uri.parse('$baseUrl/payrolls'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: json.encode(payrollData),
    );

    if (response.statusCode == 201) {
      final data = json.decode(response.body);
      return Payroll.fromJson(data['data']);
    } else {
      throw Exception('Failed to create payroll');
    }
  }

  static Future<void> markPayrollAsPaid(String token, int payrollId, String paymentMethod) async {
    final response = await http.patch(
      Uri.parse('$baseUrl/payrolls/$payrollId/mark-as-paid'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: json.encode({
        'payment_method': paymentMethod,
      }),
    );

    if (response.statusCode != 200) {
      throw Exception('Failed to mark payroll as paid');
    }
  }
}
```

### 5. Department Management

#### Get Departments
```dart
class DepartmentService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  static Future<List<Department>> getDepartments(String token) async {
    final response = await http.get(
      Uri.parse('$baseUrl/departments'),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return (data['data']['data'] as List)
          .map((json) => Department.fromJson(json))
          .toList();
    } else {
      throw Exception('Failed to load departments');
    }
  }
}
```

## Model Classes for Flutter

### Employee Model
```dart
class Employee {
  final int id;
  final String employeeId;
  final String firstName;
  final String lastName;
  final String? phone;
  final DateTime? dateOfBirth;
  final String? gender;
  final String? address;
  final String position;
  final DateTime hireDate;
  final double salary;
  final String employmentStatus;
  final bool isOnboarded;
  final Department? department;
  final User? user;

  Employee({
    required this.id,
    required this.employeeId,
    required this.firstName,
    required this.lastName,
    this.phone,
    this.dateOfBirth,
    this.gender,
    this.address,
    required this.position,
    required this.hireDate,
    required this.salary,
    required this.employmentStatus,
    required this.isOnboarded,
    this.department,
    this.user,
  });

  factory Employee.fromJson(Map<String, dynamic> json) {
    return Employee(
      id: json['id'],
      employeeId: json['employee_id'],
      firstName: json['first_name'],
      lastName: json['last_name'],
      phone: json['phone'],
      dateOfBirth: json['date_of_birth'] != null 
          ? DateTime.parse(json['date_of_birth']) 
          : null,
      gender: json['gender'],
      address: json['address'],
      position: json['position'],
      hireDate: DateTime.parse(json['hire_date']),
      salary: double.parse(json['salary'].toString()),
      employmentStatus: json['employment_status'],
      isOnboarded: json['is_onboarded'],
      department: json['department'] != null 
          ? Department.fromJson(json['department']) 
          : null,
      user: json['user'] != null 
          ? User.fromJson(json['user']) 
          : null,
    );
  }

  String get fullName => '$firstName $lastName';
}
```

### Offer Letter Model
```dart
class OfferLetter {
  final int id;
  final String offerId;
  final String candidateName;
  final String candidateEmail;
  final String? candidatePhone;
  final String position;
  final double offeredSalary;
  final String salaryCurrency;
  final DateTime offerDate;
  final DateTime joiningDate;
  final String? jobDescription;
  final String? benefits;
  final String status;
  final Department? department;

  OfferLetter({
    required this.id,
    required this.offerId,
    required this.candidateName,
    required this.candidateEmail,
    this.candidatePhone,
    required this.position,
    required this.offeredSalary,
    required this.salaryCurrency,
    required this.offerDate,
    required this.joiningDate,
    this.jobDescription,
    this.benefits,
    required this.status,
    this.department,
  });

  factory OfferLetter.fromJson(Map<String, dynamic> json) {
    return OfferLetter(
      id: json['id'],
      offerId: json['offer_id'],
      candidateName: json['candidate_name'],
      candidateEmail: json['candidate_email'],
      candidatePhone: json['candidate_phone'],
      position: json['position'],
      offeredSalary: double.parse(json['offered_salary'].toString()),
      salaryCurrency: json['salary_currency'],
      offerDate: DateTime.parse(json['offer_date']),
      joiningDate: DateTime.parse(json['joining_date']),
      jobDescription: json['job_description'],
      benefits: json['benefits'],
      status: json['status'],
      department: json['department'] != null 
          ? Department.fromJson(json['department']) 
          : null,
    );
  }
}
```

### Payroll Model
```dart
class Payroll {
  final int id;
  final String payrollId;
  final String month;
  final int year;
  final double basicSalary;
  final double allowances;
  final double bonus;
  final double overtimePay;
  final double grossSalary;
  final double taxDeduction;
  final double insuranceDeduction;
  final double otherDeductions;
  final double netSalary;
  final String paymentStatus;
  final Employee? employee;

  Payroll({
    required this.id,
    required this.payrollId,
    required this.month,
    required this.year,
    required this.basicSalary,
    required this.allowances,
    required this.bonus,
    required this.overtimePay,
    required this.grossSalary,
    required this.taxDeduction,
    required this.insuranceDeduction,
    required this.otherDeductions,
    required this.netSalary,
    required this.paymentStatus,
    this.employee,
  });

  factory Payroll.fromJson(Map<String, dynamic> json) {
    return Payroll(
      id: json['id'],
      payrollId: json['payroll_id'],
      month: json['month'],
      year: json['year'],
      basicSalary: double.parse(json['basic_salary'].toString()),
      allowances: double.parse(json['allowances'].toString()),
      bonus: double.parse(json['bonus'].toString()),
      overtimePay: double.parse(json['overtime_pay'].toString()),
      grossSalary: double.parse(json['gross_salary'].toString()),
      taxDeduction: double.parse(json['tax_deduction'].toString()),
      insuranceDeduction: double.parse(json['insurance_deduction'].toString()),
      otherDeductions: double.parse(json['other_deductions'].toString()),
      netSalary: double.parse(json['net_salary'].toString()),
      paymentStatus: json['payment_status'],
      employee: json['employee'] != null 
          ? Employee.fromJson(json['employee']) 
          : null,
    );
  }
}
```

## Error Handling

```dart
class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, {this.statusCode});

  @override
  String toString() => 'ApiException: $message (Status: $statusCode)';
}

// Usage in service methods
try {
  final response = await http.get(/* ... */);
  if (response.statusCode == 200) {
    return json.decode(response.body);
  } else {
    throw ApiException('Request failed', statusCode: response.statusCode);
  }
} catch (e) {
  if (e is ApiException) {
    // Handle API errors
    print('API Error: ${e.message}');
  } else {
    // Handle network errors
    print('Network Error: $e');
  }
  rethrow;
}
```

## State Management with Provider/Riverpod

```dart
class AuthProvider extends ChangeNotifier {
  String? _token;
  User? _user;

  String? get token => _token;
  User? get user => _user;
  bool get isAuthenticated => _token != null;

  Future<bool> login(String email, String password) async {
    try {
      final response = await AuthService.login(email, password);
      if (response['success']) {
        _token = response['data']['token'];
        _user = User.fromJson(response['data']['user']);
        notifyListeners();
        return true;
      }
      return false;
    } catch (e) {
      print('Login error: $e');
      return false;
    }
  }

  void logout() {
    _token = null;
    _user = null;
    notifyListeners();
  }
}
```

## Testing the Integration

1. **Start the Laravel server**:
   ```bash
   php artisan serve
   ```

2. **Test with the provided test script**:
   ```bash
   php test_api.php
   ```

3. **Use the default test credentials**:
   - Email: `sarah.johnson@company.com`
   - Password: `password123`

## Next Steps

1. Implement the model classes in your Flutter app
2. Set up HTTP client with proper error handling
3. Implement state management for authentication
4. Create UI screens for each feature
5. Add proper loading states and error handling
6. Implement offline caching if needed

The API is now ready for Flutter integration! 

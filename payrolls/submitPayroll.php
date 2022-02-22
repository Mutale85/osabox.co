<?php
	include("../includes/db.php");
	extract($_POST);
	$parent_id = $_SESSION['parent_id'];


	if (!empty($payrollID)) {
		#update
		$sql = $connect->prepare("UPDATE `payroll` SET `payment_type` = ?, `pay_date` = ?, `employee_id` = ?, `pay_scale` = ?, `salary_amount` = ?, `the_currency` = ?, `grosspay` = ?, `total_deductions` = ?, `net_pay` = ?, `payment_method` = ?, `bank_name` = ?, `account_number` = ?, `paid_amount` = ? WHERE id = ? ");
		$ex = $sql->execute(array($payment_type, $pay_date, $employee, $pay_scale, $salary_amount, $the_currency, $grosspay, $total_deductions, $net_pay, $payment_method, $bank_name, $account_number, $paid_amount, $payrollID));

		foreach ($allowance_amount as $key => $value) {
			$amount = $value;
			$all_id = $allowance_id[$key];
			$q = $connect->prepare("UPDATE `payroll_allowances` SET `allowance_amount` = ?, `pay_date` = ? WHERE payroll_id = ? AND parent_id = ? AND allowance_id = ?");
			$q->execute(array( $amount, $pay_date,  $payrollID, $parent_id, $all_id ));
		}

		foreach ($deduction_amount as $key => $value) {
			$amount = $value;
			$d_id = $deduction_id[$key];
			$q = $connect->prepare("UPDATE `payroll_deductions` SET `deduction_amount`= ?, `pay_date` = ? WHERE payroll_id = ? AND parent_id = ? AND deduction_id = ? ");
			$q->execute(array($amount, $pay_date, $payrollID,  $parent_id, $d_id, ));
		}

		echo "Payslip Updated";

	}else{
		$query = $connect->prepare("SELECT * FROM payroll WHERE employee_id = ? AND MONTH(pay_date) = ? ");
		$MONTH = date("m");
		$query->execute(array($employee, $MONTH));
		if ($query->rowCount() > 0) {
			echo "Payslip for ".date("F") . " for ". getStaffMemberNames($connect, $employee, $parent_id). ' has already been added';
			exit();
		}

		$sql = $connect->prepare("INSERT INTO `payroll`(`payment_type`, `pay_date`, `employee_id`, `pay_scale`, `salary_amount`, `the_currency`, `grosspay`, `total_deductions`, `net_pay`, `payment_method`, `bank_name`, `account_number`, `paid_amount`, `branch_id`, `parent_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($payment_type, $pay_date, $employee, $pay_scale, $salary_amount, $the_currency, $grosspay, $total_deductions, $net_pay, $payment_method, $bank_name, $account_number, $paid_amount, $branch_id, $parent_id));
		$payroll_id = $connect->lastInsertId();
		foreach ($allowance_amount as $key => $value) {
			$amount = $value;
			$all_id = $allowance_id[$key];
			$allowance = $allowance_name[$key];
			$q = $connect->prepare("INSERT INTO `payroll_allowances`(`payroll_id`, `employee_id`, `branch_id`, `parent_id`, `allowance_id`, `allowance_name`,  `allowance_amount`, `pay_date`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
			$q->execute(array($payroll_id, $employee, $branch_id, $parent_id, $all_id, $allowance, $amount, $pay_date));
		}

		foreach ($deduction_amount as $key => $value) {
			$amount = $value;
			$d_id = $deduction_id[$key];
			$deduction = $deduction_amount[$key];
			$q = $connect->prepare("INSERT INTO `payroll_deductions`(`payroll_id`, `employee_id`, `branch_id`, `parent_id`, `deduction_id`, `deduction_name`, `deduction_amount`, `pay_date`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
			$q->execute(array($payroll_id, $employee, $branch_id, $parent_id, $d_id, $deduction, $amount, $pay_date));
		}

		echo "Payslip Created";
	}
?>
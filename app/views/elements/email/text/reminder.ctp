Dear <?php echo $reminder['Reminder']['fname'] ?>,

Thank you for using <?php echo $company['Company']['name'] ?>. <?php echo $reminder['Reminder']['service_message'] ?> 

Service Date:		<?php echo $reminder['Reminder']['date'] ?> 
Service Time:		<?php echo $reminder['Reminder']['from_time'] ?> - <?php echo $reminder['Reminder']['to_time'] ?> 
Your Technician:	<?php echo $reminder['Reminder']['tech'] ?> 
 
<?php echo $reminder['Reminder']['tech_bio'] ?> 
 
You've Made A Great Decision! 
<?php echo $reminder['Reminder']['features_benefits'] ?> 
 
As long as <?php echo $reminder['Reminder']['technician_id'] ? "I'm" : "We're" ?> here, consider taking advantage of our other services: 
<?php echo $reminder['Reminder']['services'] ?> 
 
Are there any other services you would like? 
<?php echo $reminder['Reminder']['other_services'] ?> 
 
If you are unable to keep this appointment, please call customer service at <?php echo $company['Company']['phone'] ?> 
 
For more information, please visit our website at <?php echo $company['Company']['website_url'] ?> 
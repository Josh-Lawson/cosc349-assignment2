resource "aws_db_instance" "default" {
  allocated_storage    = 20
  db_name              = "mydb"
  engine               = "mysql"
  engine_version       = "5.7"
  instance_class       = "db.t3.micro"
  username             = "admin"
  password             = "mysqlpassword"
  parameter_group_name = "default.mysql5.7"
  skip_final_snapshot  = true
  identifier           = "recipe-database"
}
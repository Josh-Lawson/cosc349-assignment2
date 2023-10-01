resource "aws_security_group" "rds_sg" {
  name        = "rds_sg"
  description = "Allow inbound traffic to RDS instance"

  ingress {
    from_port   = 3306
    to_port     = 3306
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]  
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name = "RDS Security Group"
  }
}

resource "aws_db_instance" "default" {

  vpc_security_group_ids = [aws_security_group.rds_sg.id]

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
  publicly_accessible    = true
}

output "rds_endpoint" {
  value = aws_db_instance.default.endpoint
}
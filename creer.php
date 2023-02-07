<meta charset="utf-8">
<?php 
	$pdo1=new PDO("sqlite:stat1.db");
	$pdo2=new PDO("sqlite:stat.db");
	$table=["galilée_dang987","galilée_danggd","yagouaeecyagoua2023","kanadiebipaix98"];
	// for($i=2;$i<count($table);$i++){
		$tab=$table[3];
		$pdo2->query("
			CREATE TABLE \"$tab\" (
				\"id\"	INTEGER,
				\"date\"	TEXT NOT NULL,
				\"lecon\"	TEXT NOT NULL,
				\"ef_g\"	INTEGER NOT NULL,
				\"ef_f\"	INTEGER NOT NULL,
				\"ef_m\"	INTEGER NOT NULL,
				\"of_g\"	INTEGER NOT NULL,
				\"of_f\"	INTEGER NOT NULL,
				\"of_m\"	INTEGER NOT NULL,
				\"m_p\"	TEXT NOT NULL,
				\"ob\"	TEXT,
				PRIMARY KEY(\"id\" AUTOINCREMENT)
			)

		");
	// }
 ?>
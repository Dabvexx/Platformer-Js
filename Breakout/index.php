<!DOCTYPE php>

<html>
    <head>
        <meta charset = "utf-8" />
        <link rel = "stylesheet" type = "text/css" href="../myStyles.css">
        <title>Pong</title>
    </head>

    <body>
        <table width = "100%">
			<tr> 
				<td align = "center">
					<table width = "1500px" border = "0" style = "background-color: #F4F6F4">
						<tr> 
							<td colspan = "3">
								<!--Begin Header section -->
								<?PHP include '../includes/header.php'; ?>
								<!--End Header section -->
							</td>
						</tr> 
						<tr> 
							<td class = "leftNav">
								<!-- Begin leftNav section --> 
								<?PHP include '../includes/leftNav.php'; ?>
								<!-- End leftNav section -->
							</td> 
							<td class = "mainContent">
								<!-- Begin content section -->
								<?PHP include 'mainContent.php'; ?>
								<!-- end content section --> 
							</td> 
							<td>
								<!-- Begin rightNav section -->
								<?PHP include '../includes/rightNav.php'; ?>
								<!-- End rightNav section -->
							</td> 
						</tr> 
						<tr> 
							<td colspan = "3">
								<!-- Begin footer section -->
								<?PHP include '../includes/footer.php'; ?>
								<!-- End footer section -->
							</td> 
						</tr>
					</table>
            	</td> 
			</tr>
        </table>
    </body>
</html>
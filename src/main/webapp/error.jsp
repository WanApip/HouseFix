<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HouseFix | Error</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8eed3; padding: 50px; text-align: center; }
        .error-card { background: white; border-radius: 8px; max-width: 600px; margin: 0 auto; padding: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #d9534f; }
        .btn-home { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #734d26; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="error-card">
        <h2>An Error Occurred</h2>
        <p>
            <% 
                String error = (String) request.getAttribute("errorMessage");
                String details = (String) request.getAttribute("exceptionDetails");
                out.print(error != null ? error : "An unexpected application condition was encountered.");
                if (details != null) {
                    out.print("<br><small style='color:#777;'>" + details + "</small>");
                }
            %>
        </p>
        <a href="HouseFixController?action=home" class="btn-home">Return Home</a>
    </div>
</body>
</html>
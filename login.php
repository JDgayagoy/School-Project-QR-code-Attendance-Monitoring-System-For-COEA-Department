<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <style>
        .bg-accent-color{
            background-color: #F96D00;
        }
        .bg-second-color{
            background-color: #393E46;
        }
        .bg-primary-color{
            background-color:#222831;
        }
        .shadow-lg-white {
            box-shadow: 0 10px 15px -3px rgba(255, 255, 255, 0.2), 
                        0 4px 6px -2px rgba(255, 255, 255, 0.2);
        }
        .inputBox::placeholder {
        font-weight: bold;
        font-size: 16px;
        transition: all 0.3s ease;
        position: absolute;
        top: 50%; 
        left: 10px; 
        transform: translateY(-50%); 
        }
        .inputBox:focus::placeholder {
            font-size: 10px;
            top: 0;
            transform: translateY(0); 
        }
        .forgotBotton:hover{
            color: #F96D00;
            transition: 0.2s;
        }
    </style>
</head>
<body>
    <main class="w-screen h-screen flex px-10 relative bg-primary-color">
        <section class="absolute left-0 top-1/3 w-1/3 h-40 bg-second-color rounded-tr-full rounded-br-full flex p-4 shadow-lg-white items-center">
            <div id="orange" class="ml-4 h-3/4 w-3 bg-accent-color"></div>
            <div class="ml-3 w-auto h-full flex flex-col py-3">
                <h1 class=" text-3xl text-white font-bold italic ">Cagayan State University <span class=" font-normal">- Carig Campus</span></h1>
                <h2 class="text-white">Attendance monitoring system</h2>
            </div>
        </section>
        <section class="absolute right-20 w-auto h-auto flex flex-col items-center top-32">
            <img src="images/logo.png" alt="" width='150px'>
            <h1 class=" text-6xl font-bold text-white">WELCOME</h1>
            <form action="php/loginAction.php" method="post">
                <div class="flex flex-col gap-5 mt-10">
                    <input type="text"name="student_id" placeholder="Student ID"
                    class=" w-96 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                    <input type="password" name="password" placeholder="Password"
                    class=" w-96 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                </div>

                <div class="mt-4 w-full flex justify-end">
                    <button class="text-m font-bold text-white forgotBotton">Forgot Password?</button>
                </div>
                <input type="submit" name="submit" value="LOGIN" class=" bg-accent-color rounded-full text-lg font-bold text-white w-64 h-14 mt-8"></input>
                <p class="text-sm text-white font-semibold mt-5">Don't have an account?</p>
                <a href='register.php' class="forgotBotton text-blue-300 text-sm font-bold ">Register here!</a>
            </form>
        </section>
    </main>
</body>
</html>

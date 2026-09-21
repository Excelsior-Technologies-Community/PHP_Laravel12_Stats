<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Products</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm">

        <div class="card-body">

            <h1 class="h3 mb-3">
                Sample Products
            </h1>

            <p class="text-muted">
                This page exists as a sample Laravel Stats
                controller and route.
            </p>

            <a
                href="{{ route('stats.index') }}"
                class="btn btn-primary"
            >
                Open Statistics Dashboard
            </a>

        </div>

    </div>

</div>

</body>
</html>
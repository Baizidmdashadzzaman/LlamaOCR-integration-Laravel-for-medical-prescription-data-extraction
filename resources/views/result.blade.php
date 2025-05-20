<!DOCTYPE html>
<html>
<head>
    <title>OCR Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Medicine data extraction</h2>
                    </div>
                    <div class="card-body">
                       <pre class="bg-light p-3 rounded border">{{$markdown}}</pre>
                       <br><br>
                       <h2>Detected Medicines</h2>
                       @if (!empty($medicines))
                           <ul>
                               @foreach ($medicines as $medicine)
                                   <li>{{ $medicine }}</li>
                               @endforeach
                           </ul>
                       @else
                           <p>No medicine names detected.</p>
                       @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

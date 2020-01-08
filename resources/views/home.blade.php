@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    <br>
                       1. <a href="/user/addkey">添加公钥</a>

                       2. <a href="/user/deckey">解密数据</a>

                       3. <a href="/user/liting"><b style="color:red">验签</b></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

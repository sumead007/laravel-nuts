<nav class="navbar navbar-button">
    <div class="row">
        <div class="col-md-6">
            <div class="input-group ">
                <div class="input-group-prepend">
                    <div class="input-group-text">ชื่อผู้ใช้</div>
                </div>
                <input type="text" class="form-control" id="username" name="username"
                    value="{{ auth()->guard('user')->user()->username }}" disabled>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">จำนวนเงินคงเหลือ</div>
                </div>
                <input type="text" class="form-control" id="show_money" name="show_money"
                    value="{{ auth()->guard('user')->user()->money }}" disabled>
            </div>
        </div>
    </div>
</nav>

<style>
    .times-hidden{
        visibility: hidden !important;
    }
    .times-show{
        cursor: pointer;
        font-weight: bold !important;
        color:#cd201f!important;
        margin-left:0.25rem !important;
        font-size:larger !important;
    }
    .filter-active{
        background-color: rgb(140, 245, 195) !important;
        color: black !important;
    }
</style>

<style src="{{asset('packages/dataTables-custom/css/dataTables.bootstrap4.min.css')}}"></style>
<style src="{{asset('public/packages/dataTables-custom/css/select.dataTables.min.css')}}"></style>
<style src="{{asset('css/jquery.fancybox.min.cs')}}"></style>

<div class="card">
    <div class="card-header p-1 d-inline-block" style="background-color: rgb(43, 208, 223)">
        <span class="font-weight-bold"><i class="la la-users" aria-hidden="true"></i>&nbsp; Members List</span>
        <span><a class="btn btn-warning btn-sm float-right text-dark font-weight-bold mr-4" href="javascript:;" onclick="getMembersData()"><i class="fa fa-refresh"></i>Refresh</a></span>
    </div>
    <div class="card-body p-0">
        <div class="form-row p-2">
            
            <div class="col d-inline-flex">
                <select class="form-control searchselect" name="province_id" id="province_id" style="width: 100%;" onchange="getMembersData()">
                    <option class="text-mute" selected disabled value=""> -- Province --</option>
                    @foreach($provinces as $p)
                    <option class="form-control" value="{{ $p->id }}">{{ $p->name_en }}</option>
                    @endforeach
                </select>
                <button class="btn bg-light la la-times province_filter times-hidden font-weight-bold" onclick="filterClear(this)"></button>
            </div>
    
            <div class="col d-inline-flex">
                <select class="form-control searchselect" name="district_id" id="district_id" style="width: 100%;" onchange="getMembersData()">
                    <option class="text-mute" selected disabled value=""> -- Select Province First --</option>

                    {{-- @foreach($districts as $d)
                    <option class="form-control" value="{{ $d->id }}">{{ $d->name_en }}</option>
                    @endforeach --}}
                </select>
                <button class="btn bg-light la la-times district_filter times-hidden font-weight-bold" onclick="filterClear(this)"></button>
            </div>
            <div class="col d-inline-flex">
                <select class="form-control searchselect" name="gender_id" id="gender_id" style="width: 100%;" onchange="getMembersData()">
                    <option class="text-mute" selected disabled value=""> -- Gender --</option>
                    @foreach($genders as $g)
                        <option class="form-control" value="{{ $g->id }}">{{ $g->name_en }}</option>
                    @endforeach
                </select>
                <button class="btn bg-light la la-times gender_filter times-hidden font-weight-bold" onclick="filterClear(this)"></button>
            </div>
            <div class="col d-inline-flex">
                <select class="form-control searchselect" name="country_status" id="country_status" style="width: 100%;" onchange="getMembersData()">
                    <option class="text-mute" selected disabled value=""> -- Country --</option>
                    <option class="form-control" value="nepal">Nepal</option>
                    <option class="form-control" value="other">Other</option>
                </select>
                <button class="btn bg-light la la-times country_status_filter times-hidden font-weight-bold" onclick="filterClear(this)"></button>
            </div>
            <div class="col d-inline-flex">
                <select class="form-control searchselect" name="age_group" id="age_group" style="width: 100%;" onchange="getMembersData()">
                    <option class="text-mute" selected disabled value=""> -- select age group --</option>
                    <option class="form-control" value="Below-30">Below 30</option>
                    <option class="form-control" value="31-40">31-40</option>
                    <option class="form-control" value="41-50">41-50</option>
                    <option class="form-control" value="51-60">51-60</option>
                    <option class="form-control" value="60-Above">60 & Above</option>
                </select>
                <button class="btn bg-light la la-times age_group_filter times-hidden font-weight-bold" onclick="filterClear(this)"></button>
            </div>
        </div>
    </div>


    <div class="col" id="members_data"></div>
</div>

<script src="{{asset('packages/dataTables-custom/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('packages/dataTables-custom/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('packages/dataTables-custom/js/dataTables.select.min.js')}}"></script>
<script src="{{asset('js/jquery.fancybox.min.js')}}"></script>
<script src="{{asset('js/dependentdropdown.js')}}"></script>
<script>
    $(document).ready(function() {

        let province_id = localStorage.getItem('province_id');
        let district_id = localStorage.getItem('district_id');
        let gender_id = localStorage.getItem('gender_id');
        let age_group = localStorage.getItem('age_group');

        if(province_id)
        {
            $('#province_id option[value="'+province_id+'"').attr('selected','selected');
            $('#province_id').trigger('change');
        }
        
        if(district_id)
        {   
            setTimeout(() => {
                $('#district_id option[value="'+district_id+'"').attr('selected','selected');
            }, 600);
        }
        if(gender_id)
        {
            setTimeout(() => {
                $('#gender_id option[value="'+gender_id+'"').attr('selected','selected');
            }, 1000);
        }

        if(age_group)
        {
            if(age_group == "Below 30") age_group='Below-30';
            if(age_group == "60 & Above") age_group='60-Above';
            setTimeout(() => {
                $('#age_group option[value="'+age_group+'"').attr('selected','selected');
            }, 1500);

        }

        setTimeout(() => {
            getMembersData();
            localStorage.removeItem('province_id');
            localStorage.removeItem('district_id');
            localStorage.removeItem('gender_id');
            localStorage.removeItem('age_group');
        }, 2500);
    });

    function getMembersData() {
        let data = {
            province_id: $('#province_id').val(),
            district_id: $('#district_id').val(),
            gender_id: $('#gender_id').val(),
            country_status: $('#country_status').val(),
            age_group:$('#age_group').val(),
        }
        if($('#province_id').val()){
            $('.province_filter').removeClass('times-hidden').addClass('times-show');
        }
      
        if($('#district_id').val()){
            $('.district_filter').removeClass('times-hidden').addClass('times-show');
        }
       
        if($('#gender_id').val()){
            $('.gender_filter').removeClass('times-hidden').addClass('times-show');
        }
        if($('#country_status').val()){
            $('.country_status_filter').removeClass('times-hidden').addClass('times-show');
        }
        if($('#age_group').val()){
            $('.age_group_filter').removeClass('times-hidden').addClass('times-show');
        }

        let active_ele = document.getElementsByClassName('times-show').forEach(function(ele){
            let itm = ele.previousElementSibling;
            $(itm).addClass('filter-active');
        });

        $('#members_data').html('<div class="text-center"><img src="/css/images/loading.gif"/></div>');
        $.ajax({
            type: "POST",
            url: "/public/get-members-list",
            data: data,
            success: function(response) {
                $('#members_data').html(response);

                $('#members_data_table').DataTable({
                    searching: true,
                    paging: true,
                    ordering: true,
                    select: false,
                    bInfo: true,
                    lengthChange: false
                });
                
              

               
            }
        });
    }

    function filterClear(item){
        let element_name =item.parentElement.firstElementChild.getAttribute('name');
        if(element_name){
            $('select[name='+element_name+']').val('').trigger('change');

            $(item).removeClass('times-show').addClass('times-hidden');
            $('#'+element_name).removeClass('filter-active');
        }
    }

</script>
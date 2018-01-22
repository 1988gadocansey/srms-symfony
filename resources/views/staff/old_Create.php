@extends('layouts.app')


@section('style')
<style>
    input{
        text-transform: uppercase
    }

</style>
<script src="{!! url('public/assets/js/jquery.min.js') !!}"></script>

<script src="{!! url('public/assets/js/jquery-ui.min.js') !!}"></script>


@endsection
@section('content')
<div class="md-card-content">
    <div style="text-align: center;display: none" class="uk-alert uk-alert-success" data-uk-alert="">

    </div>



    <div style="text-align: center;display: none" class="uk-alert uk-alert-danger" data-uk-alert="">

    </div>

    @if (count($errors) > 0)


    <div class="uk-alert uk-alert-danger  uk-alert-close" style="background-color: red;color: white" data-uk-alert="">

        <ul>
            @foreach ($errors->all() as $error)
            <li>{!!$error  !!} </li>
            @endforeach
        </ul>
    </div>

    @endif


</div>
<h5 class="heading_b uk-margin-bottom">Add Staff  here</h5>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">



            <form  novalidate id="wizard_advanced_form" class="uk-form-stacked"   method="post" accept-charset="utf-8"  name="memberForm"  v-form>

                {!!  csrf_field() !!}
                <div data-uk-observe="" id="wizard_advanced" role="application" class="wizard clearfix">
                    <div class="steps clearfix">
                        <ul role="tablist">
                            <li role="tab" class="fill_form_header first current" aria-disabled="false" aria-selected="true" v-bind:class="{ 'error' : !in_payment_section}">
                                <a aria-controls="wizard_advanced-p-0" href="#wizard_advanced-h-0" id="wizard_advanced-t-0">
                                    <span class="current-info audible">current step: </span><span class="number">1</span> <span class="title">Biodata</span>
                                </a>
                            </li>
                            <li role="tab" class="payment_header disabled" aria-disabled="true"   v-bind:class="{ 'error' : in_payment_section}" >
                                <a aria-controls="wizard_advanced-p-1" href="#wizard_advanced-h-1" id="wizard_advanced-t-1">
                                    <span class="number">2</span> <span class="title">Job information</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class=" clearfix " style="box-sizing: border-box;display: block;padding:15px!important;position: relative;">

                        <!-- first section -->
                        {{-- <h3 id="wizard_advanced-h-0" tabindex="-1" class="title current">Fill Form</h3> --}}
                        <section id="fill_form_section" role="tabpanel" aria-labelledby="fill form section" class="body step-0 current" data-step="0" aria-hidden="false"   v-bind:class="{'uk-hidden': in_payment_section} ">

                            <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">First Name :</label><input type="text" id="fname" name="fname" class="md-input"   required="required"     v-model="fname"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.fname.$error.required">Please enter your first name</p>
                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Last Name :</label><input type="text" id="surname" name="surname" class="md-input"   required="required"       v-model="surname"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.surname.$error.required">Please enter your surname</p>
                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_skype">Other Names :</label><input type="text" id="othernames" name="othernames" v-form-ctrl  class="md-input"    v-model="othernames"      /><span class="md-input-bar"></span></div>

                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <label for="">Title :</label>
                                        <div class="md-input-wrapper md-input-filled">
                                            {!!   Form::select('title',array("Mr"=>"Mr","Mrs"=>"Mrs","Miss"=>"Miss" ),old('title',''),array('placeholder'=>'Select title', "class"=>"md-input","v-model"=>"title","v-form-ctrl"=>"","v-select"=>"title"))  !!}
                                            <span class="md-input-bar"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <label for="">Gender :</label>
                                        <div class="md-input-wrapper md-input-filled">
                                            {!!   Form::select('gender',array("MALE"=>"Male",'FEMALE'=>"Female"),old('gender',''),array('placeholder'=>'Select gender',"required"=>"required","class"=>"md-input","v-model"=>"gender","v-form-ctrl"=>"","v-select"=>"gender"))  !!}
                                            <span class="md-input-bar"></span>
                                        </div>
                                        <p class="uk-text-danger uk-text-small"  v-if="memberForm.gender.$error.required">Gender is required</p>
                                    </div>
                                </div>
                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">Date Hired :</label><input type="text" name="joined" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}"  v-model="joined"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                    </div>
                                </div>
                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Phone N<u>o</u> :</label><input type="text" id="phone" name="phone" class="md-input" data-parsley-type="digits" minlength="10"  required="required"   maxlength="10"   pattern='^[0-9]{10}$'  v-model="phone"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.phone.$invalid">Please enter a valid phone number of 10 digits</p>
                                    </div>
                                </div>



                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">Date of Birth :</label><input type="text" name="dob" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}" required="required"  v-model="dob"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                        <p class="uk-text-danger uk-text-small " v-if="memberForm.dob.$error.required" >Date of birth is required</p>
                                    </div>
                                </div>

                            </div>

                            <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">
                                <div class="parsley-row"  >
                                    <div class="uk-input-group">

                                        <label for="">Religious Denomination :</label>
                                        <div class="md-input-wrapper md-input-filled">
                                            {!!   Form::select('religion',$religion,old('religion',''),array("required"=>"required","class"=>"md-input","id"=>"religion","v-model"=>"religion","v-form-ctrl"=>"","style"=>"","v-select"=>"religion")   )  !!}
                                            <span class="md-input-bar"></span>
                                        </div>

                                    </div>
                                </div>







                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <label for="">Hometown Region:</label>
                                        <div class="md-input-wrapper md-input-filled">
                                            {!!   Form::select('region',$region ,array("required"=>"required","class"=>"md-input","id"=>"region","v-model"=>"region","v-form-ctrl"=>"","v-select"=>"{{old('region')}}")   )  !!}
                                            <span class="md-input-bar"></span>
                                        </div>
                                        <p class="uk-text-danger uk-text-small"  v-if="memberForm.region.$error.required">Region is required</p>
                                    </div>
                                </div>
                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Contact Address
                                                :</label><input type="text" id="contact" name="contact" class="md-input"   required="required"    v-model="contact"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.contact.$error.required">Contact Address is required</p>
                                    </div>
                                </div>


                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Hometown :</label><input type="text" id="hometown" name="hometown" class="md-input"   required="required"      v-model="hometown"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.hometown.$error.required">Hometown is required</p>
                                    </div>
                                </div>



                            </div>
                            <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Email :</label><input type="email" id="hn" name="email" class="md-input"       v-model="email"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p class="uk-text-danger uk-text-small "  v-if="memberForm.email.$invalid"  >Please enter a valid email address</p>

                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <label for="">Staff Category :</label>
                                        <div class="md-input-wrapper md-input-filled">
                                            {!!   Form::select('category',array("PERMANENT STAFF"=>"Permanent Staff ","PART TIME STAFF"=>"Part Time Staff","VISITING STAFF"=>"Visiting Staff"),old('category',''),array('placeholder'=>'Select category',"required"=>"required","class"=>"md-input","v-model"=>"category","v-form-ctrl"=>"","v-select"=>"category"))  !!}
                                            <span class="md-input-bar"></span>
                                        </div>
                                        <p class="uk-text-danger uk-text-small"  v-if="memberForm.category.$error.required">Category is required</p>
                                    </div>
                                </div>



                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">No of dependents:</label><input type="text" id="dependents" name="dependents" class="md-input"        v-model="dependents"  v-form-ctrl><span class="md-input-bar"></span></div>
                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">STAFF N<u>o</u>:</label><input type="text" id="ssnit" name="ssnit" class="md-input"   required=""     v-model="ssnit"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p class="uk-text-danger uk-text-small"  v-if="memberForm.ssnit.$error.required">Staff SSNIT is required</p>

                                    </div>
                                </div>

                            </div>


                            <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Place of residence:</label><input type="text" id="residence" name="residence" class="md-input"   required="required"      v-model="residence"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.residence.$error.required">Place of residence is required</p>
                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <label for="">Marital Status :</label>
                                        <div class="md-input-wrapper md-input-filled">
                                            {!!   Form::select('marital_status',array("MARRIED"=>"Married",'SINGLE'=>"Single"),old('marital_status',''),array('placeholder'=>'Select marital status',"required"=>"required","class"=>"md-input","v-model"=>"marital_status","v-form-ctrl"=>"","v-select"=>"marital_status"))  !!}
                                            <span class="md-input-bar"></span>
                                        </div>
                                        <p class="uk-text-danger uk-text-small"  v-if="memberForm.marital_status.$error.required">Marital Status is required</p>
                                    </div>
                                </div>



                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Next of Kin Name:</label><input type="text" id="kname" name="kname" class="md-input"   required="required"      v-model="kname"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.kname.$error.required">Name is required</p>
                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Next of Kin Phone N<u>o</u> :</label><input type="text" id="kphone" name="kphone" class="md-input" data-parsley-type="digits" minlength="10"  required="required"   maxlength="10"   pattern='^[0-9]{10}$'  v-model="kphone"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.kphone.$invalid">Please enter a valid phone number of 10 digits</p>
                                    </div>
                                </div>


                            </div>


                            <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">





                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <label for="">Staff Designation :</label>
                                        <div class="md-input-wrapper md-input-filled">
                                            {!!   Form::select('gender',array("Lecturer"=>"Lecturer",'Support'=>"Support Staff"),old('designation',''),array('placeholder'=>'Select designation',"required"=>"required","class"=>"md-input","v-model"=>"designation","v-form-ctrl"=>"","v-select"=>"designation"))  !!}
                                            <span class="md-input-bar"></span>
                                        </div>
                                        <p class="uk-text-danger uk-text-small"  v-if="memberForm.designation.$error.required">designation is required</p>
                                    </div>
                                </div>



                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Next of Kin Address:</label><input type="text" id="kaddress" name="kaddress" class="md-input"   required="required"      v-model="kaddress"  v-form-ctrl><span class="md-input-bar"></span></div>
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="memberForm.kaddress.$error.required">Kin Address is required</p>
                                    </div>
                                </div>


                            </div>


                        </section>

                        <!-- second section -->
                        {{-- <h3 id="payment-heading-1" tabindex="-1" class="title">Payment</h3> --}}
                        <section id="payment_section" role="tabpanel" aria-labelledby="payment section" class="body step-1 "  v-bind:class="{'uk-hidden': !in_payment_section} "  data-step="1"  aria-hidden="true">
                            <h2 class="heading_a">

                                <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                    <div class="parsley-row">
                                        <div class="uk-input-group">

                                            <div class="md-input-wrapper md-input-filled"><label for="wizard_email">Highest level of Education :</label><input type="text" id="education" name="education" class="md-input"   v-model="education"v-form-ctrl  ><span class="md-input-bar"></span></div>

                                        </div>
                                    </div>

                                    <div class="parsley-row">
                                        <div class="uk-input-group">

                                            <label for="">Leave Status :</label>
                                            <div class="md-input-wrapper md-input-filled">
                                                {!!   Form::select('leave',array("On Duty"=>"At Post",'On Leave'=>"On Leave"),old('leave',''),array('placeholder'=>'Select leave status',"class"=>"md-input","v-model"=>"leave","v-form-ctrl"=>"","v-select"=>"leave"))  !!}
                                                <span class="md-input-bar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="parsley-row">
                                        <div class="uk-input-group">

                                            <div class="md-input-wrapper md-input-filled"><label for="wizard_email">Position</label><input type="text" id="position" name="position" class="md-input"   v-model="position"v-form-ctrl  ><span class="md-input-bar"></span></div>

                                        </div>
                                    </div>
                                    <div class="parsley-row">
                                        <div class="uk-input-group">

                                            <div class="md-input-wrapper md-input-filled"><label for="wizard_email">Grade</label><input type="text" id="grade" name="grade" class="md-input"   v-model="form"v-form-ctrl  ><span class="md-input-bar"></span></div>

                                        </div>
                                    </div>

                                </div>

                                <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                    <div class="parsley-row">
                                        <div class="uk-input-group">

                                            <label for="">Relationship to Next of Kin :</label>
                                            <div class="md-input-wrapper md-input-filled">
                                                {!!   Form::select('krelation',array("MOTHER"=>"Mother",'FATHER'=>"Father","HUSBAND"=>"Husband","WIFE"=>"Wife","SISTER"=>"Sister","BROTHER"=>"Brother","CHILD"=>"Child"),old('krelation',''),array('placeholder'=>'Select relationship' ,"class"=>"md-input","v-model"=>"krelation","v-form-ctrl"=>"","v-select"=>"krelation"))  !!}
                                                <span class="md-input-bar"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="parsley-row">
                                        <div class="uk-input-group">

                                            <label for="">Department:</label>
                                            <div class="md-input-wrapper md-input-filled">
                                                {!!   Form::select('department',$department ,array("class"=>"md-input","id"=>"region","v-model"=>"region","v-form-ctrl"=>"","v-select"=>"department")   )  !!}
                                                <span class="md-input-bar"></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                        </section>

                    </div>
                    <div class="actions clearfix "  >
                        <ul aria-label="Pagination" role="menu">
                            <li class="button_previous " aria-disabled="true"  v-on:click="go_to_fill_form_section()"  v-show="in_payment_section==true"  >
                                <a role="menuitem" href="#previous" >
                                    <i class="material-icons"></i> Previous
                                </a>
                            </li>
                            <li class="button_next button"   v-on:click="go_to_payment_section()"  aria-hidden="false" aria-disabled="false"  v-show="memberForm.$valid && in_payment_section==false"  >
                                <a role="menuitem" href="#next"  >Next
                                    <i class="material-icons">
                                    </i>
                                </a>
                            </li>
                            <li class="button_finish "    aria-hidden="true"  v-show="memberForm.$valid && in_payment_section==true"  >

                                <button  v-show="memberForm.$valid"  class="md-btn md-btn-primary uk-margin-small-top client"  name="submit_order"  v-on:click="submit_form" type="submit">Save</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>

            <div class="uk-modal" id="confirm_modal"   >
                <div class="uk-modal-dialog"  v-el:confirm_modal>
                    <div class="uk-modal-header uk-text-large uk-text-success uk-text-center" >Confirm Data</div>
                    Are you certain of all the info
                    {{-- <div class="uk-modal-footer ">
                        <center>
                            <button class="md-btn md-btn-primary uk-margin-small-top" type="submit" name="submit_order" > Cancel</button>
                            <button class="md-btn md-btn-primary uk-margin-small-top" type="submit" name="submit_order" > Ok</button>
                        </center>
                    </div> --}}
                </div>
            </div>
        </div>

    </div>



</div>


@endsection
@section('js')

<script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
<script>
    $(document).ready(function(){
        $('select').select2({ width: "resolve" });


    });


</script>

<script>


    //code for ensuring vuejs can work with select2 select boxes
    Vue.directive('select', {
        twoWay: true,
        priority: 1000,
        params: [ 'options'],
        bind: function () {
            var self = this
            $(this.el)
                .select2({
                    data: this.params.options,
                    width: "resolve"
                })
                .on('change', function () {
                    self.vm.$set(this.name,this.value)
                    Vue.set(self.vm.$data,this.name,this.value)
                })
        },
        update: function (newValue,oldValue) {
            $(this.el).val(newValue).trigger('change')
        },
        unbind: function () {
            $(this.el).off().select2('destroy')
        }
    })


    var vm = new Vue({
        el: "body",
        ready : function() {
        },
        data : {


            options: [
            ],
            in_payment_section : false,
        },
        methods : {
            go_to_payment_section : function (event){
                UIkit.modal.confirm(vm.$els.confirm_modal.innerHTML, function(){

                    vm.$data.in_payment_section=true
                })

            },

            go_to_fill_form_section : function (event){
                vm.$data.in_payment_section=false
            }
        }
    })

</script>
<script>
    $(document).ready(function(){
        $('.client').on('click', function(e){


            var year = $('.year').val();

            UIkit.modal.confirm("Are you sure every data is accurate?? "
                , function(){
                    modal = UIkit.modal.blockUI("<div class='uk-text-center'>Saving data <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                    //setTimeout(function(){ modal.hide() }, 500) })()
                    $.ajax({

                        type: "POST",
                        url:"{{url('add_staff')}}",
                        data: $('#wizard_advanced_form').serialize(), //your form data to post goes
                        dataType: "json",
                    }).done(function(data){
                        //  var objData = jQuery.parseJSON(data);
                        modal.hide();
                        //                                    
                        //                                     UIkit.modal.alert("Action completed successfully");

                        //alert(data.status + data.data);
                        if (data.status == 'success'){
                            $(".uk-alert-success").show();
                            $(".uk-alert-success").text(data.status + " " + data.message);
                            $(".uk-alert-success").fadeOut(4000);
                            window.location.href="{{url('/staff')}}";
                        }
                        else{
                            $(".uk-alert-danger").show();
                            $(".uk-alert-danger").text(data.status + " " + data.message);
                            $(".uk-alert-danger").fadeOut(4000);
                        }


                    });
                }
            );
        });


    });</script>


@endsection         
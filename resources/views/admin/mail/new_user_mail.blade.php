<!DOCTYPE html>
<html>
  <head></head>
  <body style="font-family: 'Muli', sans-serif;text-align: center">
    <div style="text-align: center;width: 100%;font-family: open sans;max-width: 580px;float: none;margin: 0 auto;border: 1px solid #686868 ;padding-top: 30px;display: inline-block;">
      <div style="padding: 0px;text-align: center;display: inline-block;float: left;width:calc(100% - 40px);padding: 0 20px;border-box: box-sizing;">
        <!-- <div style="margin-bottom: 20px;"><img src="http://wowffers.com/backend/images/wowffersLogo.png" alt="logo"></div> -->
        <div>
          <div style="padding: 0 20px;margin-bottom: 20px;">
            <div>
              <h2 style="margin: 0 auto;font-size: 18px;margin-bottom: 10px;font-weight: normal;text-align: left;"><b>Dear, {{ucfirst($name)}}</b></h2>
            </div>
          </div>
          <div style="padding: 0 20px;">
            <div>
              <div style="font-size: 20px;margin-bottom: 10px;">Your registration for ERP access is successful!</div>
            </div>
          </div>
          <div style="padding: 0 20px;">
            <div>
              <div style="font-size: 16px;margin-bottom: 10px;">Your login credentials are.  </div>
            </div>
          </div>
          <div style="background-color: #0d4a83;color: #ffffff;font-size: 18px;padding: 20px;width: 100%;display: inline-block;box-sizing: border-box;">
            <div style="margin-bottom: 4px;"><strong>Name: </strong>{{ucfirst($name)}}</div>
            <div style="margin-bottom: 4px;"><strong>Email: </strong>{{$email}}</div>
            <div style="margin-bottom: 4px;"><strong>Password: </strong>{{$password}}</div>
          </div>
          <div style="padding: 0 20px 20px;">
            <div>
              <div style="font-size: 18px;margin: 25px auto 0 ;line-height: 26px;">There is link to login: <a href="<?= URL::route(config('Constant.login_link'))?>">Click here</a></div>
            </div>
          </div>
          <div style="padding: 0 20px 20px;">
            <div>
              <div style="font-size: 18px;margin: 25px auto 0 ;line-height: 26px;"> Please write to us on email <a href="mailto:marketing@wowffers.com" style="text-decoration:none;color: #e52534;">admin@gmail.com</a><br/>or call us on <a href="tel:1234567890" style="text-decoration:none;color: #e52534;">1234567890 / </a><a href="tel:1234567890" style="text-decoration:none;color: #e52534;">1234567890</a>for your login issues.</div>
            </div>
          </div>
          <div style="padding: 0 20px;margin-bottom: 20px;">
            <div>
              <h2 style="margin: 0 auto;font-size: 18px;margin-bottom: 10px;font-weight: normal;text-align: left;"><b>Regards </b><br><br>
                Triton Process Automation Pvt Limited<br>
                Swastik Disha Corporate Park, 613-615, 6th Floor<br>
                L.B.S. Marg, Opposite shreyas Cinema<br>
                Ghatkopar(W), Mumbai – 400086<br>
                Tel. : 022 2500 1900<br>
                nirav@tritonprocess.com <br>
                www.tritonprocess.com</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
/*import React, { Component } from 'react';
import './ncs.css';*/

class NCS extends React.Component {//primary specials class
  constructor(props) {
    super(props);
    
    let ncsdataArray = JSON.parse(this.props.ncsarray),
    ncsdataArray2 = this.mapSpecial(ncsdataArray);  
    
    this.state = {
      ncs_specials: ncsdataArray2,
      dataRoute: 'http://theme4.quirkcars.biz/wp-json/wp/v2/ncs-special?per_page=1000',
      ncs_react_atts: {"bodystyle" : "", "dealtype" : "", "condition" : "Used", "invsys" : "", "make" : "", "model" : "", "storeletter" : "", "storename" : "", "storestate" : "", "value_trade" : "blah"},
      ncsRenderCount : 0,      
      isHidden: [],
      isFormHidden: [],
      isDiscHidden: []
    }
  }  
  
  componentDidMount() {   
      /*let ncs_query = '',  
      ncs_array = [],
      ncs_rel_count = 0,
      ncs_or_rel = '',
      ncs_and_rel = '',
      ncs_or_count = 0,
      ncs_and_count = 0;
       
      const ncs_filter_comp = (name, atts) => {
          if (atts !== '') {
              if (atts.indexOf(',') > -1) {
                  ncs_array = atts.split(',');
                  if (ncs_query.indexOf('[relation]=OR') === -1){
                      ncs_or_rel = ncs_rel_count;
                      ncs_query += '&filter[meta_query]['+ncs_or_rel+'][relation]=OR'; 
                      ncs_rel_count++;                
                  }
                  for (var m in ncs_array) {
                      ncs_query += '&filter[meta_query]['+ncs_or_rel+']['+ncs_or_count+'][key]='+name+'&filter[meta_query]['+ncs_or_rel+']['+ncs_or_count+'][value]='+ncs_array[m];
                      ncs_or_count++;             
                  }
              }else{          
                  if (ncs_query.indexOf('[relation]=AND') === -1){ 
                      ncs_and_rel = ncs_rel_count;
                      ncs_query += '&filter[meta_query]['+ncs_and_rel+'][relation]=AND';
                      ncs_rel_count++;  
                  }
                  ncs_query += '&filter[meta_query]['+ncs_and_rel+']['+ncs_and_count+'][key]='+name+'&filter[meta_query]['+ncs_and_rel+']['+ncs_and_count+'][value]='+atts;
                  ncs_and_count++;
              }
          }       
      }
      
      if(this.state.ncs_react_atts.dealtype !== '') {
          ncs_query += '&filter[taxonomy]=deal_type&filter[term]='+this.state.ncs_react_atts.dealtype;
      }

      ncs_filter_comp('make_name', this.state.ncs_react_atts.make);
      ncs_filter_comp('model_name', this.state.ncs_react_atts.model);
      ncs_filter_comp('new_used', this.state.ncs_react_atts.condition);
      ncs_filter_comp('body_style', this.state.ncs_react_atts.bodystyle);
      ncs_filter_comp('store_name', this.state.ncs_react_atts.storename);
      ncs_filter_comp('store_letter', this.state.ncs_react_atts.storeletter);
      ncs_filter_comp('store_state', this.state.ncs_react_atts.storestate);*/
      //console.log(this.props.ncsarray);
      /*fetch(this.state.dataRoute)
      .then(res => res.json())
      .then(ncs_specials => this.setState((prevState, props) => {
        return { ncs_specials: ncs_specials.sort((a_ncs,b_ncs) => a_ncs.meta.ordering - b_ncs.meta.ordering).map(this.mapSpecial)};            
      })); */
      /*let ncsdataArray = JSON.parse(this.props.ncsarray),
      ncsdataArray2 = this.mapSpecial(ncsdataArray);
      this.setState({
          ncs_specials: ncsdataArray2
      }); */   
    }
  ncsToggleHidden(index, popname) {//show or hide function
      switch(popname){
      case 'calc':
          let isHidden = this.state.isHidden.slice(0);
          isHidden[index] = !isHidden[index];
          this.setState({
              isHidden
          });
          break;
      case 'form':
          let isFormHidden = this.state.isFormHidden.slice(0);
          isFormHidden[index] = !isFormHidden[index];
          this.setState({
              isFormHidden
          });     
          break;
      case 'disc':
          let isDiscHidden = this.state.isDiscHidden.slice(0);
          isDiscHidden[index] = !isDiscHidden[index];
          this.setState({
              isDiscHidden
          });
          break;
      }
  }  
  mapSpecial(special) {//creates assortment of variables from each special to be used in the render    
    if (special.total_pricing_lines > 0) {             
      var price_array = [],
      pricing_html = '',
      row_counter = 1;      
      
      for (var n in special) { 
        if (n.match(/pricing.*name$/i) || n.match(/pricing.*value$/i)) { 
          if ( n.match(/pricing.*value$/i) ) {
              special[n] = '$'+JSON.parse(special[n]).toLocaleString();
          }  
          price_array.push( special[n] ); 
        }
      }          
        
      for (var m in price_array) { 
        if (row_counter % 2 !== 0) {     
          pricing_html += '<tr><td>'+price_array[m]+'</td>';
        } else {
          pricing_html += '<td>'+price_array[m]+'</td></tr>';
        }  
        row_counter++;      
      }                   
    }   
    var modelmod = '',
    makemod = '',
    conditionMod = special.new_used_ws;
    switch(special.modelname_ws.toString()){ //patch for Ford model names and mazda make names
        case 'F150':
            modelmod = 'F-150';
            makemod = special.makename_ws;
        break;
        case 'F250':
            modelmod = 'F-250';
            makemod = special.makename_ws;
        break;   
        case 'F350':
            modelmod = 'F-350';
            makemod = special.makename_ws;
        break;    
        case 'E350':
            modelmod = 'E-350';
            makemod = special.makename_ws;
        break; 
        case 'E450':
            modelmod = 'E-450';
            makemod = special.makename_ws;
        break; 
        case 'CMax':
            modelmod = 'C-Max';
            makemod = special.makename_ws;
        break;  
        case 'F350 DRW':
            modelmod = 'F-350 DRW';
            makemod = special.makename_ws;
        break;
        case 'F550 DRW':
            modelmod = 'F-550 DRW';
            makemod = special.makename_ws;
        break; 
        case 'F650 DRW':
            modelmod = 'F-650 DRW';
            makemod = special.makename_ws;
        break;   
        case 'Mazda3':
            modelmod = special.modelname_ws;
        break; 
        case 'Mazda6':
            modelmod = special.modelname_ws;
        break; 
        default:
            modelmod = special.modelname_ws;
            makemod = special.makename_ws;
        break;  
    }
    if (conditionMod == 'Used') {
        conditionMod = 'Pre-Owned'
    }
    if(special.zero_down_lease_price == 0 || special.zero_down_lease_price == '0'){
        special.zero_down_lease_price = undefined; 
    }
    if(special.lease_price == 0 || special.lease_price == '0'){
        special.lease_price = undefined;
    }
    if(special.single_lease_price == 0 || special.single_lease_price == '0'){
        special.single_lease_price = undefined;
    }
    return {      
        id: special.id,
        specialid: special.id,
        vin: special.vin_number,
        stuck_number: special.stock_number,
        body: special.body_style,
        condition: conditionMod,      
        make: makemod, 
        model: modelmod,
        qprice: special.buy_price, 
        qsavings: special.save_up_to_amount,
        qsavingstxt: special.custom_save_up_to_text,
        finprice: special.finance_for_price,
        finterm: special.finance_for_term,
        fininterest: special.finance_for_interest_rate,
        findownpay: special.finance_for_down_payment,
        fintv: special.finance_for_trade_in_value,
        leaseprice: special.lease_price,
        leaseterm: special.lease_term,
        leasedp: special.lease_extras, 
        zdown: special.zero_down_lease_price,
        zdownterm: special.zero_down_lease_term,
        singlepay: special.single_lease_price,
        singlemiles: special.single_lease_miles,
        singleterm: special.single_lease_term,      
        apr: special.available_apr,
        aprtxt: special.apr_text,
        title: conditionMod+' '+special.year+' '+makemod+' '+modelmod+' '+special.trim_level,    
        titlenotrim: conditionMod+' '+special.year+' '+makemod+' '+modelmod,
        trim: special.trim_level,
        image: special.vehicle_image,
        //order: special.meta.ordering,
        splines: price_array,
        splineshtml: pricing_html,
        pageurl: '',
        inventoryurl: special.alt_link_url,
        tagline: special.tagline,
        marquee: special.custom_marquee_text,
        disclaimer: special.disclaimer_text,
        phone: special.sales_phone,
        storeletter: special.store_letter,
        leadsemail: special.leads_email
      }
  }  
  render() {
    this.state.ncsRenderCount++;    
	const showPriceSavings = (qname, qnumber) => {
      if (qnumber !== '' && qnumber !== '0' && qnumber !== undefined && qnumber !== null) {
          if (qname === 'Quirk Price') {
              return (<div className="salePricingBuyPrices qsThirtyThree">
                      <div className="qs_price_wrapper">
                          <div className="qs_price_title ncs_primary_color qs_bold">
                              <span>{qname}</span>
                          </div>
                          <div className="qs_price_value">
                              <span><span className="pdSign">$</span>{JSON.parse(qnumber).toLocaleString()}</span>
                          </div>
                      </div>
                  </div>);            
          } else {
              return (<div className="qs_save_wrapper">
                      <div className="qs_save_title">
                          <span>{qname}:</span>
                      </div>
                      <div className="qs_save_value ncs_primary_color">
                          <span>${JSON.parse(qnumber).toLocaleString()}</span>
                      </div>
                  </div>);
          }
      }
    }
    const showAPR = (apr, aprtxt) => {
      if (apr !== '' && apr !== undefined && apr !== null)
        return (<div className="specialAPR qsThirtyThree">
					<div className="qsAPR_title ncs_primary_color qs_bold">APR</div>
					<div className="qsAPR_value">{apr}%*</div>
                </div>);
    }
    const showLease = (dealtype, leasetype, leasedp, leaseterm) => {
      if (leasetype !== '' && leasetype !== '0' && leasetype !== undefined && leasetype !== null) 
        if (dealtype !== 'single' && dealtype !== 'finance') {       
        	return (<div className="qsLeaseValWrap">
  						<div className="qsLeaseValWrapTop">
  							<span className="ldSign">$</span><span className="qsLeaseValNumber">{leasetype.toLocaleString()}</span><span className="qsLeaseMonth">/mo</span>
						</div>
						<div className="qsLeaseValWrapBottom">
							<span>${leasedp.toLocaleString()} down | {leaseterm} mos.*</span>
						</div>
					</div>);
        } else if (dealtype === 'single') {
    		return (<div className="qsLeaseValWrap">
						<div className="qsLeaseValWrapTop">
							<span className="ldSign">$</span><span className="qsLeaseValNumber">{leasetype.toLocaleString()}</span>
					    </div>
					    <div className="qsLeaseValWrapBottom">
					    	<span>{leasedp},000 miles | {leaseterm} mos.*</span>
					    </div>
					</div>);                 
        } else if (dealtype === 'finance') {
			return (<div className="qsLeaseValWrap">
			  			<div className="qsLeaseValWrapTop">
			  				<span className="ldSign">$</span><span className="qsLeaseValNumber">{leasetype.toLocaleString()}</span><span className="qsLeaseMonth">/mo</span>
			            </div>
			            <div className="qsLeaseValWrapBottom">
			            	<span>{leaseterm} mos.*</span>
			            </div>
			        </div>);  
        }
    }
    const showCustPricing = (custpricing) => {
      if (custpricing !== '' && custpricing !== undefined && custpricing !== null)
          return (<div className="qSavings">
	        		<table className="ncs_specialpricing" > 
	                	<tbody dangerouslySetInnerHTML={{__html: custpricing}}>
	                	</tbody>        
	                </table>
                  </div>); 
    } 
    const showMarquee = (marquee) => {
      if (JSON.stringify(marquee) !== '[""]' && marquee !== undefined && marquee !== null)
          return (<div className="qMarquee ncs_primary_color">
                     <span className="qMarqueespan">{marquee}</span>
                     <div className="qMarqueeFadeL"></div>
                     <div className="qMarqueeFadeR"></div>
                  </div>);      
    }     
    return (
      <div className="NCS" >
        {(this.state.ncs_specials != 'undefined') ?
        	//this.state.ncs_specials.map((special) => 
	        	<div className="IndivSpecial qsContent" key={this.state.ncs_specials.id} id={this.state.ncs_specials.id}>	        	    
					<div className="qsContentBody">
					  <div className="ncs_img">
					  	<a className="qsNcsImgLink" href={this.state.ncs_specials.pageurl} >
					  		<img src={this.state.ncs_specials.image} alt="quirk special" />
					  	</a>
					  </div>
					  <div className="qsInnerContent">
					  <a className="qsNcsTitleLink" href={this.state.ncs_specials.pageurl} >
					  	<div className="qstitle_savings">
					  		<div className="qstitle_wrap">
					  			<h3 className="ncs_model_title ">
					          		<div className="ncs_top_title" ncs-data-name={this.state.ncs_specials.titlenotrim}>
					          	  		<span>{this.state.ncs_specials.titlenotrim}</span>
					      	  		</div>
					      	  		<div className="ncs_top_trim">
					      	  			<span dangerouslySetInnerHTML={{__html: this.state.ncs_specials.trim}}></span>
					  	  			</div>
					  			</h3>
							</div>
							<div className="ncs_saving_up_to">
								{(this.state.ncs_specials.qsavingstxt != '' && this.state.ncs_specials.qsavingstxt !== undefined && this.state.ncs_specials.qsavingstxt !== null) ? showPriceSavings(this.state.ncs_specials.qsavingstxt,this.state.ncs_specials.qsavings) : showPriceSavings('save up to',this.state.ncs_specials.qsavings)}
							</div>
					    </div>
					  </a>
					 <div className="qsBottomHalf">
					     <div className="qsLeaseContainer">
							{(this.state.ncs_react_atts.dealtype === '') ? 
								(this.state.ncs_specials.leaseprice !== '' && this.state.ncs_specials.leaseprice !== undefined && this.state.ncs_specials.zdown !== '' && this.state.ncs_specials.zdown !== 0 && this.state.ncs_specials.zdown !== '0' && this.state.ncs_specials.zdown !== undefined) ?
									<div className="qsLeaseContainerInner">
										<div className="qsLeaseTitleContainer">
											<div className="qsLeaseTitle">
												<span>Lease Today:</span>
											</div>
											<div className="qsLeaseDiscLink">
												<a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
											</div>
										</div>
										<div className="qsLeaseVals">
				    						<div className="qsLeaseValOuterWrap NCSrightborder">
				    							{showLease('lease',this.state.ncs_specials.leaseprice, this.state.ncs_specials.leasedp, this.state.ncs_specials.leaseterm)}
				    						</div>
				    						<div className="qsLeaseValOuterWrap">
				    							{showLease('zero down',this.state.ncs_specials.zdown, 0, this.state.ncs_specials.zdownterm)}
				    						</div>
										</div>
									</div>
								: (this.state.ncs_specials.leaseprice !== '' && this.state.ncs_specials.leaseprice !== undefined && (this.state.ncs_specials.zdown === '' || this.state.ncs_specials.zdown === undefined)) ?
									<div className="qsLeaseContainerInner">
										<div className="qsLeaseTitleContainer">
											<div className="qsLeaseTitle">
												<span>Lease Today:</span>
											</div>
											<div className="qsLeaseDiscLink">
												<a className="discLink"  onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
											</div>
										</div>
										<div className="qsLeaseVals">
											<div className="qsLeaseValOuterWrap">
												{showLease('lease',this.state.ncs_specials.leaseprice, this.state.ncs_specials.leasedp, this.state.ncs_specials.leaseterm)}
											</div>					            						
										</div>
									</div>
								: ((this.state.ncs_specials.leaseprice === '' || this.state.ncs_specials.leaseprice === undefined) && (this.state.ncs_specials.singlepay === '' || this.state.ncs_specials.singlepay === undefined) && this.state.ncs_specials.zdown !== '' && this.state.ncs_specials.zdown !== undefined) ?
									<div className="qsLeaseContainerInner">
										<div className="qsLeaseTitleContainer">
											<div className="qsLeaseTitle">
												<span>Lease Today:</span>
											</div>
											<div className="qsLeaseDiscLink">
												<a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
											</div>
										</div>
										<div className="qsLeaseVals">
											<div className="qsLeaseValOuterWrap">
												{showLease('zero down',this.state.ncs_specials.zdown, 0, this.state.ncs_specials.zdownterm)}
											</div>					            						
										</div>
									</div>
								: ((this.state.ncs_specials.leaseprice === '' || this.state.ncs_specials.leaseprice === undefined) && this.state.ncs_specials.singlepay !== '' && this.state.ncs_specials.singlepay !== undefined && this.state.ncs_specials.zdown !== '' && this.state.ncs_specials.zdown !== undefined) ?
									<div className="qsLeaseContainerInner">
	            						<div className="qsLeaseTitleContainer">	            							
	            							<div className="qsLeaseTitle">
                                                <span>Single Pay:</span>
                                            </div>
	            							<div className="qsLeaseDiscLink">
	            								<a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
	            							</div>
	            						</div>
	            						<div className="qsLeaseVals">
		            						<div className="qsLeaseValOuterWrap NCSrightborder qSinglePayDouble">
		            							{showLease('single',this.state.ncs_specials.singlepay, this.state.ncs_specials.singlemiles, this.state.ncs_specials.singleterm)}
		            						</div>
		            						<div className="qsLeaseValOuterWrap">
		            							{showLease('zero down',this.state.ncs_specials.zdown, 0, this.state.ncs_specials.zdownterm)}
		            						</div>
	            						</div>
	        						</div>
	    						: ((this.state.ncs_specials.leaseprice === '' || this.state.ncs_specials.leaseprice === undefined) && this.state.ncs_specials.singlepay !== '' && this.state.ncs_specials.singlepay !== undefined && (this.state.ncs_specials.zdown === '' && this.state.ncs_specials.zdown === undefined)) ?
									<div className="qsLeaseContainerInner">
	            						<div className="qsLeaseTitleContainer">	            							
            								<div className="qsLeaseTitle">
            									<span>Single Pay:</span>
            								</div>	            								
	            							<div className="qsLeaseDiscLink">
	            								<a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
	            							</div>
	            						</div>
	            						<div className="qsLeaseVals">
		            						<div className="qsLeaseValOuterWrap">
		            							{showLease('single',this.state.ncs_specials.singlepay, this.state.ncs_specials.singlemiles, this.state.ncs_specials.singleterm)}
		            						</div>											            						
	            						</div>
	        						</div>
	    						: ((this.state.ncs_specials.leaseprice === '' || this.state.ncs_specials.leaseprice === undefined) && (this.state.ncs_specials.singlepay === '' || this.state.ncs_specials.singlepay === undefined) && (this.state.ncs_specials.zdown === '' || this.state.ncs_specials.zdown === undefined) && this.state.ncs_specials.finprice !== '' && this.state.ncs_specials.finprice !== undefined) ?
									<div className="qsLeaseContainerInner">
										<div className="qsLeaseTitleContainer">
											<div className="qsLeaseTitle">
												<span>Finance Today:</span>
											</div>
											<div className="qsLeaseDiscLink">
												<a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
											</div>
										</div>
										<div className="qsLeaseVals">
											<div className="qsLeaseValOuterWrap qsFinWrap">
												{showLease('finance',this.state.ncs_specials.finprice, this.state.ncs_specials.findownpay, this.state.ncs_specials.finterm)}
											</div>
											<div className="qsLeaseValOuterWrap qsFinWrapCalc">
												<div className="qsFinanceCalcLink ncs_primary_color" >
													<a className="ncsFinCalcLaunch" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'calc')}>
    													<div>Calculate</div>
    													<div>Payments</div>		
													</a>
												</div>
											</div>
										</div>
									</div>
									:
	    								<div className="qsLeaseContainerInner">
											<div className="qsNoLeaseOptions">
												<span>Contact Us For Lease and Finance Options</span>
											</div>
										</div>
							:(this.state.ncs_react_atts.dealtype === 'lease') ? 
								<div className="qsLeaseContainerInner">
									<div className="qsLeaseTitleContainer">
										<div className="qsLeaseTitle">
											<span>Lease Today:</span>
										</div>
										<div className="qsLeaseDiscLink">
											<a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
										</div>
									</div>
									<div className="qsLeaseVals">
										<div className="qsLeaseValOuterWrap">
											{showLease('lease',this.state.ncs_specials.leaseprice, this.state.ncs_specials.leasedp, this.state.ncs_specials.leaseterm)}
										</div>					            						
									</div>
								</div>
							:(this.state.ncs_react_atts.dealtype === 'zero down') ? 
								<div className="qsLeaseContainerInner">
									<div className="qsLeaseTitleContainer">
										<div className="qsLeaseTitle">
											<span>Lease Today:</span>
										</div>
										<div className="qsLeaseDiscLink">
											<a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
										</div>
									</div>
									<div className="qsLeaseVals">
										<div className="qsLeaseValOuterWrap">
											{showLease('zero down',this.state.ncs_specials.zdown, 0, this.state.ncs_specials.zdownterm)}
										</div>					            						
									</div>
								</div>
							:(this.state.ncs_react_atts.dealtype === 'single pay') ? 
							    <div className="qsLeaseContainerInner">
                                    <div className="qsLeaseTitleContainer">                                         
                                        <div className="qsLeaseTitle">
                                            <span>Single Pay:</span>
                                        </div>
                                        <div className="qsLeaseDiscLink">
                                            <a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
                                        </div>
                                    </div>
                                    <div className="qsLeaseVals">
                                        <div className="qsLeaseValOuterWrap NCSrightborder qSinglePayDouble">
                                            {showLease('single',this.state.ncs_specials.singlepay, this.state.ncs_specials.singlemiles, this.state.ncs_specials.singleterm)}
                                        </div>
                                        <div className="qsLeaseValOuterWrap">
                                            {showLease('zero down',this.state.ncs_specials.zdown, 0, this.state.ncs_specials.zdownterm)}
                                        </div>
                                    </div>
                                </div>
							:(this.state.ncs_react_atts.dealtype === 'finance') ? 
								<div className="qsLeaseContainerInner">
									<div className="qsLeaseTitleContainer">
										<div className="qsLeaseTitle">
											<span>Finance Today:</span>
										</div>
										<div className="qsLeaseDiscLink">
											<a className="discLink" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')}><span>disclaimer</span></a>
										</div>
									</div>
									<div className="qsLeaseVals">
										<div className="qsLeaseValOuterWrap qsFinWrap">
											{showLease('finance',this.state.ncs_specials.finprice, this.state.ncs_specials.findownpay, this.state.ncs_specials.finterm)}
										</div>
										<div className="qsLeaseValOuterWrap qsFinWrapCalc">
											<div className="qsFinanceCalcLink ncs_primary_color">
												<a className="ncsFinCalcLaunch" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'calc')}>
													<div>Calculate</div>
													<div>Payments</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							: null}
						</div>	
						<div className="qsBottomMarqueeLinks">
						    {showMarquee(this.state.ncs_specials.marquee)}
    						<div className="qsBottomWrapperLinks">
        						<div className="ncs_quirk_price_wrap">						
        							{showCustPricing(this.state.ncs_specials.splineshtml)}						
        							{showAPR(this.state.ncs_specials.apr, this.state.ncs_specials.aprtxt)}
        							{showPriceSavings('Quirk Price', this.state.ncs_specials.qprice)}
        						</div>
        						<div className="specialCTAs">
                                    <a className="valueTrade" href={'/'+this.state.ncs_react_atts.value_trade}>
                                        <span>Value Trade</span>
                                    </a>
                                    <a className="viewInventory" href={this.state.ncs_specials.inventoryurl}>
                                        <span>View Inventory</span>
                                    </a>
                                    <a className="getInfo ncsReactInfo ncs_btn_color" onClick={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'form')}>
                                        <span>Get This Special</span>
                                    </a>
                                </div>
                            </div>
                        </div>
		             </div>
	              </div>
	            </div>
	            {(((this.state.ncs_specials.leaseprice === '' || this.state.ncs_specials.leaseprice === undefined) && (this.state.ncs_specials.singlepay === '' || this.state.ncs_specials.singlepay === undefined) && (this.state.ncs_specials.zdown === '' || this.state.ncs_specials.zdown === undefined) && this.state.ncs_specials.finprice !== '' && this.state.ncs_specials.finprice !== undefined) || this.state.ncs_react_atts.dealtype === 'finance') ? 
	                    (this.state.isHidden[this.state.ncs_specials.id]) ?
                            <FINCALC vprice={JSON.stringify(this.state.ncs_specials.qprice)} interest={this.state.ncs_specials.fininterest} loanterm={this.state.ncs_specials.finterm} loandp={JSON.stringify(this.state.ncs_specials.findownpay)} tradeinval={JSON.stringify(this.state.ncs_specials.fintv)} ncsClosePopup={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'calc')} ncsSpecID={this.state.ncs_specials.id}/>
	                    : null	                   
	             : null}
	            {(this.state.isFormHidden[this.state.ncs_specials.id]) ?
	                    <NCSLEADFORM ncsImg = {this.props.formimg} ncsData={this.state.ncs_specials} ncsFormClose={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id, 'form')}/> 
	              : null}
               {(this.state.isDiscHidden[this.state.ncs_specials.id]) ?
                       <NCSDISC discTitle={this.state.ncs_specials.titlenotrim} discDisclaimer={this.state.ncs_specials.disclaimer} discCloser={this.ncsToggleHidden.bind(this,this.state.ncs_specials.id,'disc')} />
                  : null}
	          </div>
    	  //)
    	: ((!this.state.ncs_specials || this.state.ncs_specials.length <= 0) && this.state.ncsRenderCount > 1) ?
    	<div className="NoSpecials">
    	  <span>No specials found at this time.  Please check back later.</span>
    	</div>
    	: ''
        }             
      </div>
    );
  }
}

class NCSDISC extends React.Component {
    constructor(props) {
        super(props);
    } 
    componentDidMount() {
        this.ncsDiscResize();
        window.addEventListener("resize", this.ncsDiscResize.bind(this));
    }
    componentWillUnmount() {
        window.removeEventListener("resize", this.ncsDiscResize.bind(this));//remove the resize event listener function when the calc is unmounted
    }
    ncsDiscResize() {//specifically for wordpress site mobile positioning of the top offset value on mobile
        var headerHeight,
        scrollTop = window.scrollY || window.pageYOffset || document.documentElement.scrollTop,
        disc,
        discPosition;
        if (document.getElementById("ncsDiscWrapperInner") !== null && typeof(document.getElementById("ncsDiscWrapperInner")) !== 'undefined') {
            disc = document.getElementById("ncsDiscWrapperInner"),
            discPosition = disc.style.position || window.getComputedStyle(disc, null).getPropertyValue("position");
            
            if (discPosition === 'absolute') {                
                headerHeight = 200;
                disc.setAttribute("style","top: "+(scrollTop - headerHeight)+"px;");
            }else {
                disc.setAttribute("style","top: 50.09%");
            }
        }
    }
    render() {
        return(
            <div className="ncsDiscWrapperOuter">
                <div className="ncsDiscBG" onClick={this.props.discCloser}></div>
                <div className="ncsDiscWrapperInner" id="ncsDiscWrapperInner">
                    <span className="ncsDiscClose ncs_primary_bgcolor" onClick={this.props.discCloser}>CLOSE X</span>
                    <h5>{this.props.discTitle}</h5>
                    <p dangerouslySetInnerHTML={{__html: this.props.discDisclaimer}}></p>
                </div>
            </div>
        );
    }
}

class FINCALC extends React.Component {//this is the finance calculator
    constructor(props) {
        super(props);
        
        this.state = {//set initial state of values to empty
            qPriceVal: '20,000',
            qRate: '2.1 %',
            qMonths: '36 months',
            qDownPayment: '500',
            qTradeIn: '5,000',
            qFinanced: '', 
            qTerm: '',
            qMPayment: ''
        };
        this.ncsCalculate = this.ncsCalculate.bind(this); //handling binding in the constructor for better performance
        this.updateVals = this.updateVals.bind(this); //handling binding in the constructor for better performance
    }
    componentWillMount() {
        this.setVals();//set all values before the calc has mounted   
    }
    componentDidMount() {           
        this.ncsCalculate();//run the calculation once the calc has mounted 
        this.ncsCalcResize();//run the resize event function once on mount
        window.addEventListener("resize", this.ncsCalcResize.bind(this));//add the resize event listener function
    } 
    componentWillUnmount() {
        window.removeEventListener("resize", this.ncsCalcResize.bind(this));//remove the resize event listener function when the calc is unmounted
    }
    ncsCalcResize() {//specifically for wordpress site mobile positioning of the top offset value on mobile
        var headerHeight,
        scrollTop = window.scrollY || window.pageYOffset || document.documentElement.scrollTop,
        calc,
        calcPosition;       
        if (document.getElementById("ncsPcalcInner") !== null && typeof(document.getElementById("ncsPcalcInner")) !== 'undefined') {
            calc = document.getElementById("ncsPcalcInner"),
            calcPosition = calc.style.position || window.getComputedStyle(calc, null).getPropertyValue("position");
            
            if (calcPosition === 'absolute') {                
                headerHeight = 200;                
                calc.setAttribute("style","top: "+(scrollTop - headerHeight)+"px;");
            }else {
                calc.setAttribute("style","top: 50%");
            }
        }
    }
    setVals(){//update initial state of values based on the properties fed into the class
        if(this.props.vprice !== '' && this.props.vprice !== undefined && this.props.vprice !== null && this.props.vprice.length >= 0) {
            this.setState({
                qPriceVal: JSON.parse(this.props.vprice).toLocaleString()
            });
        }
        if(this.props.interest !== '' && this.props.interest !== undefined && this.props.interest !== null && this.props.interest.length >= 0) {
            this.setState({
                qRate: this.props.interest+' %'
            });
        }
        if(this.props.loanterm !== '' && this.props.loanterm !== undefined && this.props.loanterm !== null && this.props.loanterm.length >= 0) {
            this.setState({
                qMonths: this.props.loanterm+' months'
            });
        }
        if(this.props.loandp !== '' && this.props.loandp !== undefined && this.props.loandp !== null && this.props.loandp.length >= 0) {
            this.setState({
                qDownPayment: JSON.parse(this.props.loandp).toLocaleString()
            });
        }
        if(this.props.tradeinval !== '' && this.props.tradeinval !== undefined && this.props.tradeinval !== null && this.props.tradeinval.length >= 0) {
            this.setState({
                qTradeIn: JSON.parse(this.props.tradeinval).toLocaleString()
            });
        }
    }
    qsLoanVals( amount ){//rounding, error catching and amortization
        var value = amount.toString().replace( /\$|\,/g ,'' );

        if( isNaN( amount ) || amount <= 0){ 
            value = '0';
        }

        var sign = ( value == ( value = Math.abs( value ) ) ),
        value = Math.floor( value * 100 + 0.50000000001 ),  
        value = Math.floor( value / 100 ).toString(); 

        for( var i = 0; i < Math.floor( ( value.length - ( 1 + i ) ) / 3 ); i++ ){
            value = value.substring( 0 , value.length - ( 4 * i + 3 ) ) + ',' + value.substring( value.length - ( 4* i + 3 ) );     
        }
        
        return ( ( ( sign ) ? '' : '-' ) + '$' + value);
    }
    ncsCalculate(){//where our calculations happen
        var monthly_payment,
        total_price,
        price = this.state.qPriceVal,
        interest_rate = this.state.qRate,
        term = this.state.qMonths,    
        down_payment = this.state.qDownPayment,
        trade_in = this.state.qTradeIn,
        // Convert into numbers.
        price = Number( price.replace( /[^0-9\.]+/g , '' ) ),
        interest_rate = Number( interest_rate.replace( /[^0-9\.]+/g , '' ) ),
        term = Number( term.replace( /[^0-9\.]+/g , '' ) ),
        trade_in = Number( trade_in.replace( /[^0-9\.]+/g , '' ) ),
        down_payment = Number( down_payment.replace( /[^0-9\.]+/g , '' ) ),
        // Do math.
        total_price = price;
        total_price -= down_payment;
        total_price -= trade_in; 
        
        if( interest_rate ){
            interest_rate /= 1200,
            monthly_payment = interest_rate * total_price / ( 1 - Math.pow( 1 + interest_rate , -term ) );            
        }else{
            monthly_payment = total_price / term;            
        } 
        
        this.setState({
            qFinanced: this.qsLoanVals(total_price),
            qMPayment: this.qsLoanVals(monthly_payment),
            qTerm: term
        });        
    } 
    updateVals(chng) {//updates our inputs based on what is typed
        this.setState({
            [chng.target.name]: chng.target.value
        });
    }    
    render(){         
        return(
            <div className="ncsPcalcContainer">
               <div className="ncsCalcBackdrop" onClick={this.props.ncsClosePopup}></div>
               <div className="ncsPcalcInnerContainer" id="ncsPcalcInner">
                   <div className="ncsCalcClose ncs_primary_bgcolor ncs_primary_bghovercolor" onClick={this.props.ncsClosePopup}>
                       <span>CLOSE X</span>
                   </div>                      
                   <div className="ncsPCalc">
                       <fieldset className="ncsCalcMainFieldset">
                           <div className="ncsCalcFieldFull">
                               <label htmlFor="qPriceVal">Vehicle Price</label>                                   
                               <input name="qPriceVal" id="ncsCalcVPr" className="ncsCalcVPrice" type="text" value={this.state.qPriceVal} onChange={this.updateVals}/>
                           </div>
                           <div className="ncsCalcFieldHalf">
                               <label htmlFor="qRate">Interest Rate %</label>
                               <input name="qRate" id="ncsCalcR" className="ncsCalcRate" type="text" value={this.state.qRate} onChange={this.updateVals}/>                                    
                           </div>
                           <div className="ncsCalcFieldHalf">
                               <label htmlFor="qMonths">Loan Term</label>
                               <input name="qMonths" id="qsLT" className="ncsLoanTerm" type="text" value={this.state.qMonths} onChange={this.updateVals}/>
                           </div>
                           <div className="ncsCalcFieldHalf">
                               <label htmlFor="qDownPayment">Down Payment $</label>
                               <input name="qDownPayment" id="ncsCalcD" className="ncsCalcDpayment" type="text" value={this.state.qDownPayment} onChange={this.updateVals}/>
                           </div>
                           <div className="ncsCalcFieldHalf">
                               <label htmlFor="qTradeIn">Trade-in Value $</label>
                               <input name="qTradeIn" id="ncsCalcTV" className="ncsCalcTradeVal" type="text" value={this.state.qTradeIn} onChange={this.updateVals}/>
                           </div>
                           <div className="ncsCalcFieldBtnContainer">
                               <button id="ncsCalcBtn" className="ncsCalcFieldBtn ncs_primary_bgcolor ncs_primary_bghovercolor" onClick={this.ncsCalculate}>CALCULATE PAYMENTS</button>
                           </div>
                       </fieldset>
                       <fieldset className="ncsCalcOutputFieldset">
                           <div className="ncsCalcPayTitle">
                               <h3 className="ncsCalcPayDetails">Payment Details</h3>
                               <div className="ncsCalcPayEst">Estimated Amount Financed: <span className="ncsCalcPayEstMoney">{this.state.qFinanced}</span></div>
                               <div className="ncsCalcPayment">Your Monthly Payment:</div>
                           </div>
                           <div className="ncsCalcOutPut">                                
                               <div className="ncsCalcOutPutIndividual ncsCalcOutPutIBig">
                                   <div className="ncsCalcCostStaticBold" id="qsVarCost">
                                       {this.state.qMPayment}
                                   </div>
                                   <div className="ncsCalcOLight ncsCalcoMonthVariable">
                                       <span>{this.state.qTerm}</span> Months
                                   </div>
                               </div>                                
                           </div>
                           <div className="ncsCalcDisclaimer">
                               <span>Does not include Sales Tax and Fees</span>
                           </div>
                       </fieldset>
                   </div>
               </div>
           </div>           
        );
    }     
}

class NCSLEADFORM extends React.Component {
    constructor(props) {
        super(props);
        
        this.state = {
              formData: [],
              customer_first: '',
              customer_last: '',
              customer_email: '',
              customer_phone: '',
              customer_comments: '',
              wants_lease: '',
              wants_zero_down: '',
              wants_single: '',
              wants_finance: '',
              wants_apr: '',
              wants_savings: '',
              errorFN: '',
              errorLN: '',
              errorEmail: '',
              errorPhone: '',
              errorPHP: ''
        };
        this.formChange = this.formChange.bind(this);
        this.formSubmit = this.formSubmit.bind(this);
    } 
    componentDidMount() {
        this.ncsFormResize();//run the resize event function once on mount
        window.addEventListener("resize", this.ncsFormResize.bind(this));//add the resize event listener function
    } 
    componentWillUnmount() {
        window.removeEventListener("resize", this.ncsFormResize.bind(this));//remove the resize event listener function when the calc is unmounted
    }
    ncsFormResize() {//specifically for wordpress site mobile positioning of the top offset value on mobile
        var headerHeight,
        scrollTop = window.scrollY || window.pageYOffset || document.documentElement.scrollTop,
        form,
        formPosition;
        if (document.getElementById("ncsSpecialsFormContainer") !== null && typeof(document.getElementById("ncsSpecialsFormContainer")) !== 'undefined') {
            form = document.getElementById("ncsSpecialsFormContainer"),
            formPosition = form.style.position || window.getComputedStyle(form, null).getPropertyValue("position");
            
            if (formPosition === 'absolute') {                
                headerHeight = 200;
                form.setAttribute("style","top: "+(scrollTop - headerHeight)+"px;");
            }else {
                form.setAttribute("style","top: 50.09%");
            }
        }
    }
    formPhoneNumber(s) {
        var pInitial = (""+s).replace(/\D/g, ''),
        pMatch = pInitial.match(/^(\d{3})(\d{3})(\d{4})$/);
        return (!pMatch) ? null : "("+pMatch[1] + ") " + pMatch[2] + "-" + pMatch[3];
    }
    formChange(chng) {
        this.setState({
            [chng.target.name]: chng.target.value
        });
    }
    formSubmit(event) {
        event.preventDefault();
        
        var formFN = this.state.customer_first,
        formLN = this.state.customer_last,
        formEmail = this.state.customer_email,
        formPhone = this.state.customer_phone,
        formComments = this.state.customer_comments,        
        formLease = this.state.wants_lease,
        formZD = this.state.wants_zero_down,
        formSingle = this.state.wants_single,
        formFinance = this.state.wants_finance,
        formAPR = this.state.wants_apr,
        formSavings = this.state.wants_savings,
        oColor = '#c00;',
        formPhoneregEx = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/,
        formEmailRegEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
        errors = false,
        form_info = 'customer_first='+formFN+'&customer_last='+formLN+'&special_model='+this.props.ncsData.model+'&from_page='+window.location.pathname.replace(/\//g, '')+'&ref_post_id='+this.props.ncsData.id; 
        
        this.setState({
            errorFN: '',
            errorLN: '',
            errorEmail: '',
            errorPhone: ''
        });
        
        if (formFN == '' || formFN.length <= 1) {//first name check
            if (formFN === '') {
                this.setState({
                    errorFN: 'this field is required'
                });
            }else if (formFN.length <= 1) {
                this.setState({
                    errorFN: 'please use 2 or more characters'
                });
            }
            document.getElementById('customer_first').setAttribute('style','border-color: '+oColor);
            errors = true;
        }else{
            document.getElementById('customer_first').removeAttribute('style');
        }        
        if (formLN == '' || formLN.length <= 1) {//last name check
            if (formLN === '') {
                this.setState({
                    errorLN: 'this field is required'
                });
            }else if (formLN.length <= 1) {
                this.setState({
                    errorLN: 'please use 2 or more characters'
                });
            }
            document.getElementById('customer_last').setAttribute('style','border-color: '+oColor);
            errors = true;
        }else{
            document.getElementById('customer_last').removeAttribute('style');
        }
        if (formEmail == '' && formPhone == '') {
            this.setState({                
                errorEmail: 'you must provide either an email or a phone number',
                errorPhone: 'you must provide either an email or a phone number'
            });
            document.getElementById('customer_email').setAttribute('style','border-color: '+oColor);
            document.getElementById('customer_phone').setAttribute('style','border-color: '+oColor);
            errors = true;
        }else {
            document.getElementById('customer_email').removeAttribute('style');
            document.getElementById('customer_phone').removeAttribute('style');
            
            if(formEmail.length > 0) {
                if(!formEmail.match(formEmailRegEx)) {
                    this.setState({                
                        errorEmail: 'please enter a valid email address'                       
                    });
                    document.getElementById('customer_email').setAttribute('style','border-color: '+oColor);
                    errors = true;
                }else{
                    document.getElementById('customer_email').removeAttribute('style');
                    form_info+= '&customer_email='+formEmail;
                }
            }
            if(formPhone.length > 0) {
                if(!formPhone.match(formPhoneregEx)) {
                    this.setState({                
                        errorPhone: 'please enter a valid 9 digit phone number'                       
                    });
                    document.getElementById('customer_phone').setAttribute('style','border-color: '+oColor);
                    errors = true;
                }else{
                    document.getElementById('customer_phone').removeAttribute('style');
                    form_info += '&customer_phone='+formPhone;
                }
            }
        }
        if(formComments != '' && formComments.length > 0) {
            form_info += '&customer_comments='+formComments;
        }
        if(formLease != '' && formLease.length > 0) {
            form_info += '&wants_lease='+formLease;
        }
        if(formZD != '' && formZD.length > 0) {
            form_info += '&wants_zero_down='+formZD;
        }
        if(formSingle != '' && formSingle.length > 0) {
            form_info += '&wants_single='+formSingle;
        }
        if(formFinance != '' && formFinance.length > 0) {
            form_info += '&wants_finance='+formFinance;
        }
        if(formAPR != '' && formAPR.length > 0) {
            form_info += '&wants_apr='+formAPR;
        }
        if(formSavings != '' && formSavings.length > 0) {
            form_info += '&wants_savings='+formSavings;
        }
        /*if(!errors) {
            document.getElementById('ncsReactFormSubmit').setAttribute('style','opacity: .7;');
            document.getElementById('ncsReactFormSubmit').setAttribute('disabled','disabled;');
            var data = {
                type: "POST",
                action: 'ncs_form_push_action',
                contents: form_info,
            };
            var NCSFormPost = 
            jQuery.post(ajaxurl, data, function(response) { //submit the form data      
                response = jQuery.trim(response);           
                if( response.substr(0,1) == '1' ) { //if all is well send the customer to the thank you page
                    var urlElems = response.split( "|" );
                    window.location.href = "http://" + window.location.hostname + "/" + urlElems[1] + "/?sID=" + urlElems[2];
                }else {
                  //alert(response);
                    if( response.substr(0,1) == '0' ) { //catch server-side errors from submitted data
                        var errs = response.replace("0-",""),
                        errsArr = errs.split("|");  
                        
                        this.setState({                
                            errorPHP: errsArr[1]                              
                        });
                        
                        NCSFormPost.abort();
                        document.getElementById('ncsReactFormSubmit').setAttribute('style','opacity: 1;');
                        document.getElementById('ncsReactFormSubmit').removeAttribute('disabled');
                    }
                }
            });
        } */       
    }
    render() {
        const formCheckboxes = (leaseprice, zdown, singlepay, finprice, apr, qsavings) => {
            var hasLease = false,
            hasZD = false,
            hasSingle = false,
            hasFinance = false,
            hasAPR = false,
            hasSavings = false,
            hasCheckboxes = false;
            
            if (leaseprice !== undefined && leaseprice !== null && leaseprice.length > 0 && leaseprice !== '' && leaseprice !== '0'){
                hasLease = true;
                hasCheckboxes = true;
            }
            if (zdown !== undefined && zdown !== null && zdown.length > 0 && zdown !== '' && zdown !== '0'){
                hasZD = true;
                hasCheckboxes = true;
            }
            if (singlepay !== undefined && singlepay !== null && singlepay.length > 0 && singlepay !== '' && singlepay !== '0'){
                hasSingle = true;
                hasCheckboxes = true;
            }
            if (finprice !== undefined && finprice !== null && finprice.length > 0 && finprice !== '' && finprice !=='0'){
                hasFinance = true;
                hasCheckboxes = true;
            }
            if (apr !== undefined && apr !== null && apr.length > 0 && apr !== ''){
                hasAPR = true;
                hasCheckboxes = true;
            }
            if (qsavings !== undefined && qsavings !== null && qsavings.length > 0 && qsavings !== '' && qsavings !=='0'){
                hasSavings = true;
                hasCheckboxes = true;
            }
            
            if (hasCheckboxes) {
                return(
                        <div className="NCSFormOptions">
                            <div className="NCSFormOptionsCenterDiv">
                                <h5 className="ncs_primary_color">i'm interested in...&nbsp;<span className="FormGrey">(optional)</span></h5>
                                {(hasLease) ?
                                    <div className="NCSIndividualOptions">
                                        <div className="NCSOptionChecker">
                                            <input type="checkbox" className="checkInterest NCSFormCheckbox" name="wants_lease" tabindex="6" value="Interested in lease w/money down." onChange={this.formChange}/>
                                            <label className="NCSCheckboxLabel ncs_checker_color"></label>
                                        </div>
                                        <div className="NCSOptionText">
                                            <span>&nbsp;&nbsp;This lease price with money down</span>
                                        </div>
                                    </div>
                                : null
                                }
                                {(hasZD) ?
                                    <div className="NCSIndividualOptions">
                                        <div className="NCSOptionChecker">
                                            <input type="checkbox" className="checkInterest NCSFormCheckbox" name="wants_zero_down" tabindex="7" value="Interested in zero down lease." onChange={this.formChange}/>
                                            <label className="NCSCheckboxLabel ncs_checker_color"></label>
                                        </div>
                                        <div className="NCSOptionText">
                                            <span>&nbsp;&nbsp;This zero-down lease price</span>
                                        </div>
                                    </div>
                                : null
                                }
                                {(hasSingle) ?
                                    <div className="NCSIndividualOptions">
                                        <div className="NCSOptionChecker">
                                            <input type="checkbox" className="checkInterest NCSFormCheckbox" name="wants_single" tabindex="6" value="Interested in single pay lease." onChange={this.formChange}/>
                                            <label className="NCSCheckboxLabel ncs_checker_color"></label>
                                        </div>
                                        <div className="NCSOptionText">
                                            <span>&nbsp;&nbsp;This single pay lease price</span>
                                        </div>
                                    </div>
                                : null
                                }
                                {(hasFinance) ?
                                    <div className="NCSIndividualOptions">
                                        <div className="NCSOptionChecker">
                                            <input type="checkbox" className="checkInterest NCSFormCheckbox" name="wants_finance" tabindex="6" value="Interested in financing." onChange={this.formChange}/>
                                            <label className="NCSCheckboxLabel ncs_checker_color"></label>
                                        </div>
                                        <div className="NCSOptionText">
                                            <span>&nbsp;&nbsp;This finance price</span>
                                        </div>
                                    </div>
                                : null
                                }
                                {(hasAPR) ?
                                    <div className="NCSIndividualOptions">
                                        <div className="NCSOptionChecker">
                                            <input type="checkbox" className="checkInterest NCSFormCheckbox" name="wants_apr" tabindex="8" value="Interested in promotional APR." onChange={this.formChange}/>
                                            <label className="NCSCheckboxLabel ncs_checker_color"></label>
                                        </div>
                                        <div className="NCSOptionText">
                                            <span>&nbsp;&nbsp;This promotional APR</span>
                                        </div>
                                    </div>
                                : null
                                } 
                                {(hasSavings) ?
                                    <div className="NCSIndividualOptions">
                                        <div className="NCSOptionChecker">
                                            <input type="checkbox" className="checkInterest NCSFormCheckbox" name="wants_savings" tabindex="9" value="Interested in purchase savings." onChange={this.formChange}/>
                                            <label className="NCSCheckboxLabel ncs_checker_color"></label>
                                        </div>
                                        <div className="NCSOptionText">
                                            <span>&nbsp;&nbsp;This quirk price</span>
                                        </div>
                                    </div>
                                : null
                                }                               
                            </div>
                      </div>
                );
            }
        }       
        return (
            <div className="reactFormContainer">
                <div className="ncsReactFormBackdrop"  onClick={this.props.ncsFormClose}></div>
                <div className="reactSpecialsFormContainer" id="ncsSpecialsFormContainer">
                    <span className="close-dialog ncs_primary_bgcolor" onClick={this.props.ncsFormClose}>CLOSE X</span>
                    <form name="getSpecialForm-" id={'getSpecialForm-'+this.props.ncsData.specialid} className="ncs-lead-form" rel={this.props.ncsData.specialid}   onSubmit={this.formSubmit}>
                        <div className="formTitle">
                            <h4>
                                <div className="speacliasFormHeaderOne">Inquire about this</div> 
                                <div className="speacliasFormHeaderTwo">{this.props.ncsData.title}</div>                 
                            </h4>
                        </div>
                        <div className="FormClickCallWrap">
                            <div className="FormClickCall ncs_form_img_outline_color">
                                <a href={'tel: '+this.formPhoneNumber(this.props.ncsData.phone)}><img className="ncs_form_img_bg" src={this.props.ncsImg} /><span>{this.formPhoneNumber(this.props.ncsData.phone)}</span></a>
                            </div>
                            <div className="FormClickCallOr ncs_primary_color">
                                <span>- or -</span>
                            </div>  
                        </div>      
                        <div className="NCSFormOptionWrapper">
                            <div className="NCSFormOptionWrapperOne">
                                <div className="ncsFormOptionIndividual">
                                    <label className="ncsFormOptionLabel ncs_primary_color">First Name</label>
                                    <input type="text" className="customer_first" id="customer_first" name="customer_first" value={this.state.customer_first} onChange={this.formChange} tabindex="1"/>
                                    <p className="NCSRequiredFormParagraph">{this.state.errorFN}</p>
                                </div>
                                <div className="ncsFormOptionIndividual">
                                    <label className="ncsFormOptionLabel ncs_primary_color">Last Name</label>
                                    <input type="text" className="customer_last" id="customer_last" name="customer_last" value={this.state.customer_last} onChange={this.formChange} tabindex="2"/>
                                    <p className="NCSRequiredFormParagraph">{this.state.errorLN}</p>
                                </div>
                                <div className="ncsFormOptionIndividual">
                                    <label className="ncsFormOptionLabel ncs_primary_color">Email Address</label>
                                    <input type="email" className="customer_email" id="customer_email" name="customer_email" value={this.state.customer_email} onChange={this.formChange} tabindex="3"/>
                                    <p className="NCSRequiredFormParagraph">{this.state.errorEmail}</p> 
                                </div>
                                <div className="ncsFormOptionIndividual">
                                    <label className="ncsFormOptionLabel ncs_primary_color">Phone Number</label>
                                    <input type="text" className="customer_phone" id="customer_phone"  name="customer_phone" value={this.state.customer_phone} onChange={this.formChange} tabindex="4"/>
                                    <p className="NCSRequiredFormParagraph">{this.state.errorPhone}</p>
                                </div>
                            </div>
                            <div className="NCSFormOptionWrapperTwo">
                            	<div className="ncsFormOptionIndividual">
                                    <label className="ncsFormOptionLabel ncs_primary_color">Comments <span className="FormGrey">(optional)</span></label>
                                    <textarea id="customer_comments" name="customer_comments" value={this.state.customer_comments} onChange={this.formChange} tabindex="5"></textarea>
                                    <input type="hidden" name="special_model" value={this.props.ncsData.model} />
                                    <input type="hidden" name="from_page" value={window.location.pathname.replace(/\//g, '')} />
                                    <input type="hidden" name="ref_post_id" value={this.props.ncsData.id} />
                                </div>		          
                                {formCheckboxes(this.props.ncsData.leaseprice, this.props.ncsData.zdown, this.props.ncsData.singlepay, this.props.ncsData.finprice, this.props.ncsData.apr, this.props.ncsData.qsavings)} 
                            </div>
                        </div>
                        <button type="submit" className="ncsFormSubmit ncs_btn_color" id="ncsReactFormSubmit" name="special_submit" rel={this.props.ncsData.specialid}>Get This Special</button>
                        <p className="NCSRequiredFormParagraph ncsBottmError">{this.state.errorPHP}</p>
                        <p className="formDisclaimer ncs_primary_link_color" dangerouslySetInnerHTML={{__html: this.props.ncsData.disclaimer}}></p>
                    </form>
                </div>
            </div>
        );
    }
}

var ncsElLength = document.getElementsByClassName("qsPage").length;

if (ncsElLength > 0) {
    for (var i = 0; i < ncsElLength; i++) {
        ReactDOM.render(
        	<NCS ncsid={document.getElementsByClassName('qsPage')[i].dataset.ncsid} ncsarray={document.getElementsByClassName('qsPage')[i].dataset.ncsarray} formimg={document.getElementsByClassName('qsPage')[i].dataset.formimg}/>,
            document.getElementsByClassName('qsPage')[i]
        );
    }
}    

var qbtype = document.documentElement;
qbtype.setAttribute('data-btype', navigator.userAgent);

//export default NCS;

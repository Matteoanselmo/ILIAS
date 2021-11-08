import { expect } from 'chai';

import ddmodel from '../src/drilldown.model.js';
import ddmapping from '../src/drilldown.mapping.js';
import drilldown from '../src/drilldown.main.js';


describe('drilldown', function() {
   
    it('components are defined', function() {
        expect(ddmodel).to.not.be.undefined;
        expect(ddmapping).to.not.be.undefined;
        expect(drilldown).to.not.be.undefined;
    });
    
    var dd_model = ddmodel();
    var l0 = dd_model.actions.addLevel('root'),
        l1 = dd_model.actions.addLevel('1', l0.id),
        l11 = dd_model.actions.addLevel('11', l1.id),
        l2 = dd_model.actions.addLevel('2', l0.id),
        l21 = dd_model.actions.addLevel('21', l2.id),
        l211 = dd_model.actions.addLevel('211', l21.id);
    
    it('model creates levels', function() {
        expect(l0.label).to.eql('root');
        expect(l0.id).to.eql('0');
        expect(l0.parent).to.eql(null);

        expect(l11.label).to.eql('11');
        expect(l11.id).to.eql('2');
        expect(l11.parent).to.eql('1');
    });
    
    it('levels are engaged/disengaged properly', function() {
        expect(dd_model.actions.getCurrent()).to.eql(l0);
        expect(l1.engaged).to.be.false;

        dd_model.actions.engageLevel(l1.id);
        expect(l1.engaged).to.be.true;
        expect(dd_model.actions.getCurrent()).to.eql(l1);

        dd_model.actions.engageLevel(l211.id);
        expect(l1.engaged).to.be.false;
        expect(l211.engaged).to.be.true;
        expect(dd_model.actions.getCurrent()).to.eql(l211);
    });


    var dd = drilldown(
        ddmodel(),
        ddmapping()
    );

    it('public interface is defined on main component', function() {
        expect(dd.init).to.be.an('function');
    });


});


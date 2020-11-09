<?php
require '../../app_code/cmncde/connect_pg.php';
echo '<?xml version="1.0"?>

<definitions name="RhoSoapService" 
             targetNamespace="' . $app_url . 'xchange/SoapServer.php"
             xmlns:tns="' . $app_url . 'xchange/SoapServer.php"   
             xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
             xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" 
             xmlns="http://schemas.xmlsoap.org/wsdl/">    
    
    <documentation>Definition for a web service called RhoSoapService
        which can be used to add or retrieve books from a collection.
    </documentation>
        
        
    <types>
        <xsd:schema targetNamespace="' . $app_url . 'xchange/server.php">
            <xsd:element name="getStudentName">
                <xsd:complexType>
                    <xsd:sequence>
                        <xsd:element name="ID" type="xsd:string" />
                    </xsd:sequence>
                </xsd:complexType>
            </xsd:element>
            <xsd:element name="getStudentNameResponse">
                <xsd:complexType>
                    <xsd:sequence>
                        <xsd:element name="StudentName" type="xsd:string"></xsd:element>
                    </xsd:sequence>
                </xsd:complexType>
            </xsd:element>
            <xsd:element name="hello">
                <xsd:complexType>
                    <xsd:sequence>
                        <xsd:element name="Somebody" type="xsd:string"></xsd:element>
                    </xsd:sequence>
                </xsd:complexType>
            </xsd:element>
            <xsd:element name="helloResponse">
                <xsd:complexType>
                    <xsd:sequence>
                        <xsd:element name="SomebodyResponse" type="xsd:string"></xsd:element>
                    </xsd:sequence>
                </xsd:complexType>
            </xsd:element>
        </xsd:schema>
    </types>
    
    <message name="getStudentNameRequest">
        <part name="reqParamStudID" type="xsd:string" element="tns:getStudentName"/>
    </message>
    <message name="getStudentNameResponse">
        <part name="resParamStudNm" type="xsd:string" element="tns:getStudentName"/>
    </message>
    <message name="helloRequest">
        <part name="reqParams" type="xsd:string" element="hello"/>
    </message>
    <message name="helloResponse">
        <part name="resParams" type="xsd:string" element="hello"/>
    </message>
    <portType name="RhoPortType">
        <operation name="getStudentName">
            <input message="tns:getStudentNameRequest"/>
            <output message="tns:getStudentNameResponse"/>
        </operation>
        <operation name="hello">
            <input message="tns:helloRequest"/>
            <output message="tns:helloResponse"/>
        </operation>
    </portType>
    <binding name="RhoBinding" type="tns:RhoPortType">
        <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
        
        <operation name="getStudentName">
            <soap:operation soapAction="urn:RhoNamespace#getStudentName"/>
            <input>
                <soap:body use="encoded"
                           namespace="urn:RhoNamespace"
                           encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
            </input>
            <output>
                <soap:body use="encoded"
                           namespace="urn:RhoNamespace"
                           encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
            </output>
        </operation>
        <operation name="hello">
            <soap:operation soapAction="urn:RhoNamespace#hello"/>
            <input>
                <soap:body use="encoded"
                           namespace="urn:RhoNamespace"
                           encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
            </input>
            <output>
                <soap:body use="encoded"
                           namespace="urn:RhoNamespace"
                           encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
            </output>
        </operation>
    </binding>
    <service name="RhoSoapService">
        <documentation>Returns a greeting string.
        </documentation>
        <port name="RhoPort" binding="tns:RhoBinding">
            <soap:address
                location="' . $app_url . 'xchange/SoapServer.php"/>
        </port>
    </service>
</definitions>';

?>
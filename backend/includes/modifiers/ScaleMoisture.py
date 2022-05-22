class ScaleMoisture:

    @staticmethod
    def function(value: int, dry: int, wet: int) -> tuple[float]:
        """
        returns a percent value from the reading of the moisture sensor.

        :rtype: float
        :param value: value
        :param dry: the dry value
        :param wet: the wet value
        :return: percent value
        """
        if value < wet:
            m = 100
        else:
            m = ((dry - value) * 100.0) / (dry - wet)

        if m < 0:
            m = 0.0

        result = (round(m, 2),)

        return result